import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: menu keuangan pada anjungan tidak bisa di buka #10840', () => {
  
  test('fix: perbaikan menu keuangan pada anjungan tidak bisa di buka', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10840',
    },
  }, async ({ page }) => {
    
    // ===== STEP 1: Buat Menu Anjungan Baru =====
    await page.goto('/anjungan_menu/form');
    await expect(page.locator('h1')).toContainText('Menu');

    // 1.1 Isi nama menu
    await page.locator('input[name="nama"]').fill('Keuangan');

    // 1.2 Pilih jenis link "Artikel Keuangan"
    await page.locator('select#link_tipe').selectOption({ value: '6' });

    // 1.3 Tunggu dan pilih artikel keuangan pertama yang tersedia
    const artikelDropdown = page.locator('select#artikel_keuangan');
    await expect(artikelDropdown).toBeVisible();
    await artikelDropdown.selectOption({ index: 1 });
    const selectedArtikelValue = await artikelDropdown.inputValue();

    // 1.4 Simpan form
    await page.getByRole('button', { name: /Simpan/ }).click();

    // 1.5 Verifikasi berhasil disimpan dan kembali ke halaman daftar menu
    await expect(page.locator('div.alert.alert-success')).toBeVisible();
    await expect(page).toHaveURL(/.*\/anjungan_menu/);

    // ===== STEP 2: Verifikasi Link di Halaman Anjungan Mandiri =====
    await page.goto('/anjungan-mandiri');
    
    // 2.1 Cari link menu yang baru dibuat berdasarkan teks
    const menuLink = page.getByRole('link', { name: 'Keuangan' });
    await expect(menuLink).toBeVisible();

    // 2.2 Verifikasi href-nya sudah benar
    const linkUrl = await menuLink.getAttribute('href');
    expect(linkUrl).toContain('/first/' + selectedArtikelValue);

    // 2.3 Klik link dan pastikan tidak 404
    await menuLink.click();
    
    // Halaman artikel yang valid seharusnya memiliki judul artikel dalam tag <h2>
    await expect(page.locator('h2.post-title')).toBeVisible();
    await expect(page.locator('h1')).not.toContainText(/Not Found|Tidak Ditemukan/);
    await expect(page).toHaveURL(new RegExp('.*/first/' + selectedArtikelValue));
  });
});
