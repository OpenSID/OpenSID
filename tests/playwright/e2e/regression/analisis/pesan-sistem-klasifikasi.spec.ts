// Issue: #9512 — Sistem menampilkan pesan tidak sesuai pada klasifikasi
// https://github.com/OpenSID/OpenSID/issues/9512
import { test, expect} from '../../../utils/regressionTest';


test.describe('Bug/error: Sistem menampilkan pesan "Klasifikasi surat berhasil diimpor" meskipun tidak ada data yang diimpor #9512', () => {
  test('fix: perbaiki form impor klasifikasi surat', {
    annotation: {
        type: 'issue',
        description: 'https://github.com/OpenSID/OpenSID/issues/9512'
    }
  }, async ({ page }) => {
    await page.goto('klasifikasi');

    await page.getByRole('link', { name: ' Impor' }).click();
    await expect(page.getByText('Berkas Klasifikasi Surat :')).toBeVisible();
    await page.locator('button#ok').click();
    await expect(page.getByText('Berkas Klasifikasi Surat :')).toBeVisible();
  });
});