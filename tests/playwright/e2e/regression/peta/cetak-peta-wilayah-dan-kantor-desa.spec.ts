// Issue: #11267 — Fitur cetak peta wilayah desa pada Identitas Desa tidak dapat digunakan
// https://github.com/OpenSID/OpenSID/issues/11267
import { test, expect } from '../../../utils/regressionTest';

test.describe('Bug/error: Fitur cetak peta wilayah desa pada menu Identitas Desa → Maps/Wilayah tidak dapat digunakan #11267', () => {
  test('fix: cetak peta wilayah desa menampilkan template cetakan dan tidak blank', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11267',
    },
  }, async ({ page }) => {
    // 1. Uji halaman Peta Wilayah Desa
    await page.goto('identitas_desa/maps/wilayah');

    // Pastikan elemen peta wilayah ter-render
    await expect(page.locator('#tampil-map')).toBeVisible();

    // Pastikan kontrol browser print terpasang di peta
    await expect(page.locator('.leaflet-control-browser-print')).toBeVisible();

    // Pastikan elemen template cetak dengan atribut leaflet-browser-print-content ada di dalam DOM
    await expect(page.locator('table[leaflet-browser-print-content]')).toBeAttached();

    // 2. Uji halaman Peta Lokasi Kantor Desa
    await page.goto('identitas_desa/maps/kantor');

    // Pastikan elemen peta kantor ter-render
    await expect(page.locator('#tampil-map')).toBeVisible();

    // Pastikan kontrol browser print terpasang di peta
    await expect(page.locator('.leaflet-control-browser-print')).toBeVisible();

    // Pastikan elemen template cetak dengan atribut leaflet-browser-print-content ada di dalam DOM
    await expect(page.locator('table[leaflet-browser-print-content]')).toBeAttached();
  });
});
