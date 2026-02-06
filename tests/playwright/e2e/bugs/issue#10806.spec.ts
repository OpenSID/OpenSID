import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Tombol Tinjau Surat masih Muncul saat Selesai Cetak dan diklik error #10806', () => {
  test('fix: perbaiki tombol Tinjau Surat masih Muncul saat Selesai Cetak dan diklik error', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10806',
    },
  }, async ({ page }) => {
    await page.goto('/surat/form/sistem-surat-keterangan-penduduk');

    await page.locator('.select2-selection').first().click();
    await page.locator('.select2-search__field').fill('a');    
    await page.locator('.select2-results__option--highlighted').click();
    await page.getByRole('textbox', { name: 'Masukkan Keperluan' }).fill('Perlu');
    await page.getByRole('button', { name: ' Lanjut' }).click();
    await expect(page.getByRole('heading', { name: 'Tinjau Surat' })).toBeVisible();
    await page.getByRole('link', { name: ' Tinjau PDF' }).click();
    await page.getByRole('button', { name: ' Cetak' }).click();

    // Pastikan semua tombol/link yang dihilangkan setelah cetak tidak muncul lagi
    await expect(page.getByRole('link', { name: /Tinjau PDF/i })).toHaveCount(0);
    await expect(page.getByRole('button', { name: /Cetak/i })).toHaveCount(0);
    await expect(page.getByRole('button', { name: /Konsep/i })).toHaveCount(0);
    await expect(page.getByRole('button', { name: /Kembali/i })).toHaveCount(0);
    await expect(page.getByRole('button', { name: /Ubah Surat/i })).toHaveCount(0);
    await expect(page.getByRole('button', { name: /Pengaturan/i })).toHaveCount(0);
  });
});
