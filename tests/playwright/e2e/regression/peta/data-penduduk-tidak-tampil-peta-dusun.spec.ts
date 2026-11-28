// Issue: #10987 — Data penduduk tidak tampil benar pada peta wilayah dusun
// https://github.com/OpenSID/OpenSID/issues/10987
import { test, expect } from '../../../utils/regressionTest';


test.describe('data penduduk tidak tampil benar pada peta wilayah dusun #10987', () => {
  test('fix: perbaiki data penduduk tidak tampil benar pada peta wilayah dusun', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10987',
    },
  }, async ({ page }) => {
    await page.goto('/gis/clear');

    await page.getByRole('checkbox', { name: 'Peta Wilayah Dusun' }).check();
    await page.locator('path').nth(1).click();
    await page.getByRole('link', { name: ' Statistik Penduduk' }).click();
    await page.getByRole('link', { name: 'Umur (Rentang)' }).click();
    await expect(page.getByText('Jenis Kelompok')).toBeVisible();
  });
});
