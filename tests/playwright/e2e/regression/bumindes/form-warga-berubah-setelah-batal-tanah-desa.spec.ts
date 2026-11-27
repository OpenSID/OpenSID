// Issue: #10732 — Form warga desa berubah setelah klik tombol batal pada buku tanah di desa
// https://github.com/OpenSID/OpenSID/issues/10732
import { test, expect } from '../../../utils/regressionTest';


test.describe('Bug/error: Form Warga Desa berubah setelah klik tombol Batal pada Buku Tanah di Desa #10732', () => {
  test('fix: perbaiki form warga desa setelah klik tombol batal', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10732',
    },
  }, async ({ page }) => {
    await page.goto('bumindes_tanah_desa');
    await page.getByTitle('Ubah Data').nth(1).click();
    await expect(page.getByText('Cari Penduduk')).toBeVisible();
    await page.getByRole('button', { name: ' Batal' }).click();
    await expect(page.getByText('Cari Penduduk')).toBeVisible();
  });
});
