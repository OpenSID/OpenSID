// Issue: #10926 — Perbarui link panduan untuk impor gform ke data analisis
// https://github.com/OpenSID/OpenSID/issues/10926
import { test, expect } from '../../../utils/regressionTest';


test.describe('Bug/error: Perbarui link panduan untuk impor gform ke data analisis #10926', () => {
  test('fix: sesuaikan link panduan untuk impor gform ke data analisis', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10926',
    },
  }, async ({ page }) => {
    await page.goto('analisis_master');

    await page.getByText('Tambah Analisis Baru').click();
    await page.getByRole('link', { name: ' Impor dari Google Form' }).click();

    await expect(page.locator('#validasi')).toContainText('ID Google Form (Panduan mendapatkan ID Google Form dapat Anda akses [disini] ).');
  });
});
