// Issue: #10615 — Ubah sub kategori pada titik terkunci
// https://github.com/OpenSID/OpenSID/issues/10615
import { test, expect } from '../../../utils/regressionTest';


test.describe('Bug/error: Ubah Sub Kategori pada titik tekunci #10615', () => {
  test('fix: perbaiki ubah sub kategori point status nya menjadi tidak aktif', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10615',
    },
  }, async ({ page }) => {
    await expect(page.getByRole('gridcell', { name: 'Ya' })).toBeVisible();
    await page.getByRole('link', { name: '' }).click();
    await page.getByText('Simpan').click();
    await expect(page.getByRole('gridcell', { name: 'Ya' })).toBeVisible();
  });
});
