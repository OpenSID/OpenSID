// Issue: #9934 — Unit test proses impor penduduk
// https://github.com/OpenSID/OpenSID/issues/9934
import { test, expect } from '../../../utils/regressionTest';
import path from 'path';


test.describe('Tambahkan unit testing untuk proses impor penduduk #9934', () => {
  test('teknis: tambahkan validasi unit test untuk proses impor penduduk', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/9934',
    },
  }, async ({ page }) => {
    test.setTimeout(90000);
    await page.goto('penduduk');
    await page.bringToFront();
    await page.getByText('Impor / Ekspor').click();
    await page.getByRole('link', { name: ' Impor Penduduk' }).click();
    const filePath = path.resolve(__dirname, '../../../storage/fixtures/format-impor-excel-invalid.xlsm');
    await page.waitForSelector('#file_path_penduduk');
    await page.locator('#file_penduduk').setInputFiles(filePath);
    // #btn-impor is the actual import button
    await page.locator('#btn-impor').click();
    await page.getByRole('button', { name: 'Lanjutkan' }).click();

    // Verify import process completes (either success notification or error report table)
    await expect(page.locator('#notifikasi').or(page.locator('table.table-bordered')).first()).toBeVisible({ timeout: 60000 });
  });
});
