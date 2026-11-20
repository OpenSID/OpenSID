// Issue: #10930 — Gagal import data penduduk jika nama ayah/ibu berisi karakter tidak valid
// https://github.com/OpenSID/OpenSID/issues/10930
import { test, expect } from '../../../utils/regressionTest';
import path from 'path';


test.describe('Bug: gagal import data penduduk jika nama ayah/ ibu berisi karakter tidak valid #10930', () => {
  test('fix: perbaiki import penduduk data nama ayah dan ibu', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10930',
    },
  }, async ({ page }) => {
    test.setTimeout(90000);
    await page.goto('penduduk');
    await page.getByText('Impor / Ekspor').click();
    await page.getByRole('link', { name: ' Impor Penduduk' }).click();
    const filePath = path.resolve(__dirname, '../../../storage/format-impor-excel.xlsm');
    await page.locator('#file_penduduk').setInputFiles(filePath);
    await page.locator('#btn-impor').click();
    await page.getByRole('button', { name: 'Lanjutkan' }).click();
    // Verify import completes without crash — fix for #10930 (invalid chars in nama_ayah/ibu
    // no longer abort the entire import; individual rows are sanitized or skipped instead)
    await expect(page.locator('#notifikasi')).toContainText('berhasil diimpor', { timeout: 60000 });
  });
});
