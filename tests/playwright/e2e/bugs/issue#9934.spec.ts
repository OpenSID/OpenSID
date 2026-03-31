import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Tambahkan unit testing untuk proses impor penduduk #9934', () => {
  test('teknis: tambahkan validasi unit test untuk proses impor penduduk', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/9934',
    },
  }, async ({ page }) => {
    await page.goto('penduduk');
    await page.getByText('Impor / Ekspor').click();
    await page.getByRole('link', { name: ' Impor Penduduk' }).click();
    const filePath = require.resolve('@test/storage/fixtures/format-impor-excel-invalid.xlsm');
    await page.waitForSelector('#file_path_penduduk');
    await page.locator('#file_penduduk').setInputFiles(filePath);
    await page.getByRole('link', { name: ' Impor Data Penduduk' }).click();
    await page.getByRole('button', { name: 'Lanjutkan' }).click();
    
    // Verifikasi struktur error report sesuai dengan contoh output
    await expect(page.locator('table.table-bordered')).toBeVisible();
    
    // Verifikasi ada statistik error
    await expect(page.locator('dt:has-text("Jumlah Data Gagal")')).toBeVisible();
    await expect(page.locator('dt:has-text("Jumlah Data Ganda")')).toBeVisible();
    await expect(page.locator('dt:has-text("Rincian Pesan")')).toBeVisible();
    await expect(page.locator('dt:has-text("Total Data Berhasil")')).toBeVisible();

    await expect(page.getByRole('rowgroup')).toContainText('2) Nama wajib diisi');
  });
});