import { test, expect } from '@playwright/test';

// Playwright test untuk memastikan fungsi getBaseLayers memilih Mapbox
// ketika JENIS_PETA diset ke salah satu nilai Mapbox (3/4/5).
// Issue: https://github.com/OpenSID/OpenSID/issues/11121

test('ISSUE-11121: getBaseLayers memilih Mapbox saat JENIS_PETA mapbox', async ({ page }) => {
  // Buka halaman pengaturan lapak (menggunakan baseURL dari playwright.config)
  await page.goto('/layanan-mandiri/produk/pengaturan');

  // Tunggu sampai fungsi getBaseLayers tersedia
  await page.waitForFunction(() => typeof (window as any).getBaseLayers === 'function');

  // Set variabel global untuk mensimulasikan setting admin
  await page.evaluate(() => {
    (window as any).MAPBOX_KEY = 'test-mapbox-token';
    (window as any).JENIS_PETA = '3'; // nilai 3 diasumsikan Mapbox Streets

    // Buat elemen peta dummy
    const el = document.createElement('div');
    el.id = 'pw-test-map';
    el.style.width = '800px';
    el.style.height = '600px';
    document.body.appendChild(el);
  });

  // Inisialisasi Leaflet map dan panggil getBaseLayers
  await page.evaluate(() => {
    const L = (window as any).L;
    (window as any).pwMap = L.map('pw-test-map');
    (window as any).pwLayers = (window as any).getBaseLayers((window as any).pwMap, (window as any).MAPBOX_KEY, (window as any).JENIS_PETA);
  });

  // Cek apakah setidaknya satu layer tile mengandung string 'mapbox' di URL
  const hasMapboxLayer = await page.evaluate(() => {
    const layers = (window as any).pwLayers || {};
    for (const k of Object.keys(layers)) {
      const layer = layers[k];
      const url = layer && (layer._url || layer._tileUrl || '');
      if (typeof url === 'string' && url.toLowerCase().includes('mapbox')) return true;
    }
    return false;
  });

  expect(hasMapboxLayer).toBeTruthy();
});
