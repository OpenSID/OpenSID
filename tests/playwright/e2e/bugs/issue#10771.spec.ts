import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: tombol tinjaupdf tidak rapi di tampilan mobile #10771', () => {
  test('fix: perbaikan tombol pratijau surat tidak rapi di tampilan mobile', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10771',
    },
  }, async ({ page }) => {
    await page.goto('/surat/form/surat-keterangan-penduduk');

    // 1. Isi Data Pemohon (Pilih dari select2)
    await page.locator('.select2-selection').first().click();
    await page.locator('.select2-search__field').fill('a');
    
    // 2. Tunggu dan pilih penduduk pertama dari hasil search
    await page.locator('.select2-results__option--highlighted').click();

    // 3. Klik tombol Lanjut untuk masuk ke halaman Tinjau Surat
    await page.getByRole('button', { name: ' Lanjut' }).click();

    // 4. Validasi halaman Tinjau Surat
    await expect(page.getByRole('heading', { name: 'Tinjau Surat' })).toBeVisible();

    // 5. Assert semua tombol responsive ada dan terlihat
    // Tombol Kembali
    await expect(page.locator('a#back.btn')).toBeVisible();
    
    // Tombol Konsep (jika ada berdasarkan kondisi)
    const konsepBtn = page.locator('a#konsep.btn');
    if (await konsepBtn.isVisible()) {
      await expect(konsepBtn).toHaveClass(/visible-xs-block/);
    }
    
    // Tombol Tinjau PDF
    await expect(page.locator('a#preview-pdf.btn')).toBeVisible();
    await expect(page.locator('a#preview-pdf.btn')).toHaveClass(/visible-xs-block/);
    
    // Tombol Ubah Surat
    await expect(page.locator('a#ubah-surat.btn')).toBeVisible();
    await expect(page.locator('a#ubah-surat.btn')).toHaveClass(/visible-xs-block/);
    
    // Tombol Pengaturan
    await expect(page.locator('a#pengaturan.btn')).toBeVisible();
    await expect(page.locator('a#pengaturan.btn')).toHaveClass(/visible-xs-block/);

    // 6. Validasi bahwa semua tombol punya class inline di desktop
    await expect(page.locator('a#preview-pdf.btn')).toHaveClass(/visible-sm-inline-block/);
    await expect(page.locator('a#ubah-surat.btn')).toHaveClass(/visible-sm-inline-block/);
    await expect(page.locator('a#pengaturan.btn')).toHaveClass(/visible-sm-inline-block/);
  });
});
