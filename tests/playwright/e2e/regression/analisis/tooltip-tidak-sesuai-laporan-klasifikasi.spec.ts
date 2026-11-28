// Issue: #9663 — Tooltip tidak sesuai pada ikon centang laporan hasil klasifikasi
// https://github.com/OpenSID/OpenSID/issues/9663
import { test, expect } from '../../../utils/regressionTest';


test.describe('Bug/error: Tooltip Tidak Sesuai pada Ikon Centang Laporan Hasil Klasifikasi #9663', () => {
  test('fix: Sesuaikan tombol lihat laporan hasil klasifikasi', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/9663',
    },
  }, async ({ page }) => {
    await page.goto('analisis_laporan/2');
    await expect(page.getByRole('gridcell', { name: '' }).first()).toBeVisible();
    await expect(page.getByRole('row', { name: '1  5201140104126994' }).getByRole('link')).toBeVisible();
    await page.getByRole('row', { name: '1  5201140104126994' }).getByRole('link').click();
  });
});