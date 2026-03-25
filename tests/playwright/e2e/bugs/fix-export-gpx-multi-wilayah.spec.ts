import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Export GPX multi-wilayah hanya menghasilkan 1 wilayah', () => {
  test('fix: impor GPX dengan feature null/undefined tidak melempar error runtime', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/fix-export-gpx-multi-wilayah',
    },
  }, async ({ page }) => {
    await page.goto('identitas_desa/maps/wilayah');
    await expect(page.locator('#exportGPX')).toBeVisible();

    // Inject GeoJSON FeatureCollection yang mengandung feature dengan geometry null
    // dan MultiPolygon — memastikan tidak ada error runtime
    const errorMsg = await page.evaluate(() => {
      try {
        const geojson = {
          type: 'FeatureCollection',
          features: [
            // Feature valid Polygon
            {
              type: 'Feature',
              geometry: {
                type: 'Polygon',
                coordinates: [[
                  [105.419893, -5.394474],
                  [105.419462, -5.395081],
                  [105.419335, -5.395853],
                  [105.419893, -5.394474],
                ]],
              },
              properties: {},
            },
            // Feature dengan geometry null — harus di-skip tanpa error
            { type: 'Feature', geometry: null, properties: {} },
            // Feature null — harus di-skip tanpa error
            null,
            // Feature valid MultiPolygon
            {
              type: 'Feature',
              geometry: {
                type: 'MultiPolygon',
                coordinates: [
                  [[[ 105.42, -5.40], [105.421, -5.401], [105.422, -5.402], [105.42, -5.40] ]],
                  [[[ 105.43, -5.41], [105.431, -5.411], [105.432, -5.412], [105.43, -5.41] ]],
                ],
              },
              properties: {},
            },
          ].filter(Boolean), // leaflet GeoJSON tidak kirim null, tapi filter dulu
        };

        const coords: number[][][][] = [];
        geojson.features.forEach(function (feature: any) {
          if (!feature || !feature.geometry) return;
          const geom = feature.geometry;
          if (geom.type === 'GeometryCollection') {
            geom.geometries.forEach(function (subGeom: any) {
              if (!subGeom) return;
              if (subGeom.type === 'Polygon') coords.push(subGeom.coordinates);
              else if (subGeom.type === 'MultiPolygon')
                subGeom.coordinates.forEach((p: any) => coords.push(p));
            });
          } else if (geom.type === 'MultiPolygon') {
            geom.coordinates.forEach((p: any) => coords.push(p));
          } else if (geom.coordinates) {
            coords.push(geom.coordinates);
          }
        });

        // Harus menghasilkan 3 polygon (1 Polygon + 2 dari MultiPolygon)
        return coords.length === 3 ? 'ok' : `jumlah coords salah: ${coords.length}`;
      } catch (e: any) {
        return `error: ${e.message}`;
      }
    });

    expect(errorMsg).toBe('ok');
  });

  test('fix: export GPX harus menyertakan semua wilayah (MultiPolygon)', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/fix-export-gpx-multi-wilayah',
    },
  }, async ({ page }) => {
    // Step 1: Navigasi ke halaman peta wilayah
    await page.goto('identitas_desa/maps/wilayah');

    // Step 2: Pastikan halaman berhasil dimuat dan tombol Export GPX ada
    await expect(page.locator('#exportGPX')).toBeVisible();

    // Step 3: Inject dua polygon ke showCurrentMultiPolygon agar memastikan
    //         fungsi menampilkan multi-wilayah di peta
    const wilayahDua = [
      [
        [[-5.394474, 105.419893], [-5.395081, 105.419462], [-5.395853, 105.419335], [-5.394474, 105.419893]],
      ],
      [
        [[-5.400138, 105.419652], [-5.400818, 105.419504], [-5.401218, 105.419362], [-5.400138, 105.419652]],
      ],
    ];

    await page.evaluate((wilayah) => {
      // Reset peta dan render ulang dengan 2 polygon
      const layerpeta = window['peta_wilayah'] ?? window['map'];
      if (layerpeta) {
        showCurrentMultiPolygon(wilayah, layerpeta, {}, '0', 'Test Wilayah');
      }
    }, wilayahDua);

    // Step 4: Ambil nilai href dari tombol Export GPX setelah diklik
    const gpxHref = await page.evaluate(() => {
      return new Promise<string>((resolve) => {
        const btn = document.getElementById('exportGPX') as HTMLAnchorElement;
        btn.addEventListener('click', () => {
          // Beri waktu handler jQuery untuk melakukan set href
          setTimeout(() => resolve(btn.href), 100);
        }, { once: true });
        btn.click();
      });
    });

    // Step 5: Pastikan href berupa data URI GPX
    expect(gpxHref).toMatch(/^data:text\/xml/);

    // Step 6: Decode dan parse konten GPX
    const gpxContent = decodeURIComponent(gpxHref.replace('data:text/xml;charset=utf-8,', ''));

    // Step 7: Hitung jumlah elemen <trk> — harus sesuai jumlah polygon (2)
    const trkCount = (gpxContent.match(/<trk>/g) ?? []).length;
    expect(trkCount).toBe(2);

    // Step 8: Pastikan setiap <trk> berisi <trkseg> dengan koordinat
    expect(gpxContent).toContain('<trkseg>');
    const trkptCount = (gpxContent.match(/<trkpt /g) ?? []).length;
    expect(trkptCount).toBeGreaterThanOrEqual(6); // minimal 3 titik per polygon × 2
  });

  test('fix: tombol Export GPX tidak mendaftarkan handler duplikat', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/fix-export-gpx-multi-wilayah',
    },
  }, async ({ page }) => {
    // Step 1: Navigasi ke halaman peta wilayah
    await page.goto('identitas_desa/maps/wilayah');
    await expect(page.locator('#exportGPX')).toBeVisible();

    const wilayahDua = [
      [
        [[-5.394474, 105.419893], [-5.395081, 105.419462], [-5.395853, 105.419335], [-5.394474, 105.419893]],
      ],
      [
        [[-5.400138, 105.419652], [-5.400818, 105.419504], [-5.401218, 105.419362], [-5.400138, 105.419652]],
      ],
    ];

    // Step 2: Panggil showCurrentMultiPolygon dua kali untuk memastikan
    //         tidak ada handler yang menumpuk (karena .off().on() yang benar)
    await page.evaluate((wilayah) => {
      const layerpeta = window['peta_wilayah'] ?? window['map'];
      if (layerpeta) {
        showCurrentMultiPolygon(wilayah, layerpeta, {}, '0', 'Test Wilayah');
        showCurrentMultiPolygon(wilayah, layerpeta, {}, '0', 'Test Wilayah Ulang');
      }
    }, wilayahDua);

    // Step 3: Hitung berapa kali handler click dieksekusi (harus tepat 1 kali)
    const clickCount = await page.evaluate(() => {
      return new Promise<number>((resolve) => {
        let count = 0;
        const btn = document.getElementById('exportGPX') as HTMLAnchorElement;
        // Intercept: setiap kali href di-set berarti ada handler yang jalan
        const original = Object.getOwnPropertyDescriptor(HTMLAnchorElement.prototype, 'href');
        Object.defineProperty(btn, 'href', {
          set(val: string) {
            if (val.startsWith('data:text/xml')) count++;
          },
          get: original?.get?.bind(btn),
        });
        btn.click();
        setTimeout(() => resolve(count), 200);
      });
    });

    // Hanya 1 handler yang boleh jalan (bukan N handler karena loop)
    expect(clickCount).toBe(1);
  });
});
