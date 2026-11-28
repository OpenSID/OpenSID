// Issue: #10733 — Catatan perhitungan tidak ditampilkan pada data perolehan TKD yang sudah tersimpan
// https://github.com/OpenSID/OpenSID/issues/10733
import { test, expect } from '../../../utils/regressionTest';


test.describe('Bug/error: Catatan Perhitungan Tidak Ditampilkan pada Data Perolehan TKD yang Sudah Tersimpan #10733', () => {
  test('fix: perbaiki catatan perhitungan tidak tampil pada data perolehan TKD yang sudah tersimpan', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10733',
    },
  }, async ({ page }) => {
    await page.goto('bumindes_tanah_kas_desa');
    await expect(page.getByRole('cell', { name: 'APB DESA' }).first()).toBeVisible();
    await page.getByTitle('Ubah Data').first().click();
    await expect(page.getByRole('combobox', { name: 'APB Desa' })).toBeVisible();
    await expect(page.getByText('Catatan : Luas Tanah Total = Asli Milik Desa')).toBeVisible();
    await expect(page.getByText('Catatan : Luas Tanah Total = sawah + Tegal + Kebun + Tambak / Kolam + Tanah')).toBeVisible();
  });
});
