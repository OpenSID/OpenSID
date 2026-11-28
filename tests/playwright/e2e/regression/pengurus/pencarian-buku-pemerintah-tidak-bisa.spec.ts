// Issue: #9461 — Tidak bisa melakukan pencarian pada buku pemerintah desa
// https://github.com/OpenSID/OpenSID/issues/9461
import { test, expect } from '../../../utils/regressionTest';


test.describe('Tidak bisa melakukan pencarian pada Buku Pemerintah Desa #9461', () => {
  test('fix: perbaiki pencarian pada Buku Pemerintah Desa', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/9461',
    },
  }, async ({ page }) => {
    await page.goto('pengurus');

    await expect(page.getByRole('gridcell', { name: 'Tidak ditemukan data yang sesuai' })).not.toBeVisible();
    await page.getByRole('searchbox', { name: 'Cari:' }).fill('aaaaaa');
    await expect(page.getByRole('gridcell', { name: 'Tidak ditemukan data yang sesuai' })).toBeVisible();
    await page.getByRole('searchbox', { name: 'Cari:' }).fill('');
    await expect(page.getByRole('gridcell', { name: 'Tidak ditemukan data yang sesuai' })).not.toBeVisible();
  });
});
