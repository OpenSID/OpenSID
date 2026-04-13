import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: peta desa tidak muncul di menu pemetaan #11020', () => {
  test('fix: perbaiki peta wilayah desa tidak tampil', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10987',
    },
  }, async ({ page }) => {
    await page.goto('/gis/clear');

    // Tunggu peta dimuat
    await page.waitForSelector('#map', { timeout: 10000 });

    // Tunggu elemen tersedia lalu Toggle/Hover control layer Leaflet (layer ke-4)
    const controlLayer = page.locator('.leaflet-control-layers').nth(3);
    await controlLayer.waitFor({ state: 'visible' });
    await controlLayer.hover();

    const desaCheckbox = page.getByRole('checkbox', { name: 'Peta Wilayah Desa', exact: true });
    await expect(desaCheckbox).toBeVisible();
  });
});
