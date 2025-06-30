import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: GPX Harus Diimpor Dua Kali Agar Data Muncul di Peta Wilayah #9710', () => {
  test('fix: perbaiki peta tidak tampil saat impor gpx', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/9710',
    },
  }, async ({ page }) => {
    await page.goto('identitas_desa/maps/wilayah');

    // pilih file
    const fileInput = await page.locator('input[type="file"]');
    const gpxPath = path.resolve(__dirname, '../../storage/OpenSID.gpx');
    await fileInput.setInputFiles(gpxPath);

    // pastikan koordinat muncul di peta (misal dalam popup atau marker)
    // Contoh: cek apakah ada elemen dengan class leaflet-marker-icon atau popup koordinat
    await expect(page.locator('.leaflet-marker-icon')).toBeVisible();
  });
});