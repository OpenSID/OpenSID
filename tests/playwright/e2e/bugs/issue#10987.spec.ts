import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

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
