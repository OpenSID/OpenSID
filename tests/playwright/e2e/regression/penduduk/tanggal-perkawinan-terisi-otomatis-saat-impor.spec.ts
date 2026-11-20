// Issue: #9717 — Tanggal perkawinan (hasil impor) terisi otomatis sesuai tanggal impor
// https://github.com/OpenSID/OpenSID/issues/9717
import { test, expect } from '../../../utils/regressionTest';
import path from 'path';


test.describe('Bug/error: Tanggal perkawinan (hasil impor) terisi otomatis sesuai tgl impor #9717', () => {
  test('fix: perbaiki import penduduk data perkawinan', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/9717',
    },
  }, async ({ page }) => {
    test.setTimeout(90000);
    await page.goto('penduduk');
    await page.getByText('Impor / Ekspor').click();
    await page.getByRole('link', { name: ' Impor Penduduk' }).click();
    const filePath = path.resolve(__dirname, '../../../storage/format-impor-excel.xlsm');
    await page.waitForSelector('#file_path_penduduk');
    // #file_penduduk is the actual hidden file input (#file_path_penduduk is display-only text)
    await page.locator('#file_penduduk').setInputFiles(filePath);
    // Use #btn-impor (not the old link selector)
    await page.locator('#btn-impor').click();
    await page.getByRole('button', { name: 'Lanjutkan' }).click();
    // Verify import completes successfully
    await expect(page.locator('#notifikasi')).toContainText('berhasil diimpor', { timeout: 60000 });
  });
});
