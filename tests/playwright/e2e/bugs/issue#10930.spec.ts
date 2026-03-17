import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug: gagal import data penduduk jika nama ayah/ ibu berisi karakter tidak valid #10930', () => {
  test('fix: perbaiki import penduduk data nama ayah dan ibu', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10930',
    },
  }, async ({ page }) => {
    await page.goto('penduduk');
    await page.getByText('Impor / Ekspor').click();
    await page.getByRole('link', { name: ' Impor Penduduk' }).click();
    await page.locator('#file_path_penduduk').click();
    const filePath = path.resolve(__dirname, '../../storage/format-impor-excel.xlsm');
    await page.locator('#file_path_penduduk').setInputFiles(filePath);
    await page.getByRole('link', { name: ' Impor Data Penduduk' }).click();
    await page.getByRole('button', { name: 'Lanjutkan' }).click();
    await page.getByText('Data penduduk berhasil diimpor').click();
    await page.getByRole('link', { name: ' Kembali Ke Data Penduduk' }).click();
    await page.getByRole('link', { name: '8788888888888456' }).click();
    await expect(page.locator('#maincontent')).toContainText('-');
  });
});