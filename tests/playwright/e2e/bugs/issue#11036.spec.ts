import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
    storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: peta desa ketika dicetak tampil kecil #11036', () => {
    test('fix: perbaiki peta desa ketika dicetak tampil kecil', {
        annotation: {
            type: 'issue',
            description: 'https://github.com/OpenSID/OpenSID/issues/11036',
        },
    }, async ({ page }) => {
        await page.goto('/gis/clear');
        await page.waitForFunction(() => typeof (window as any).L !== 'undefined', null, { timeout: 15000 });
        await page.waitForTimeout(3000);

        // Print control harus muncul dengan mode Landscape & Portrait
        const printControl = page.locator('.leaflet-control-browser-print');
        await expect(printControl).toBeVisible();
        await printControl.hover();
        await page.waitForTimeout(500);
        await expect(page.locator('.browser-print-mode')).toHaveCount(2);

        // printLayer harus raster TileLayer (bukan MapboxGL) & fitBounds tanpa maxZoom
        const result = await page.evaluate(() => {
            const L = (window as any).L;
            const map = Object.values(L.Map._maps || {})[0] as any;
            if (!map?.printControl) return { printLayer: 'no-control', fitBounds: false, noMaxZoom: false, noModifyOriginal: false };

            const pl = map.printControl.options.printLayer;
            const printLayer = !pl ? 'none' : pl instanceof L.MapboxGL ? 'mapboxgl' : pl._url ? 'tilelayer' : 'unknown';

            const src = (window as any).cetakPeta?.toString() || '';
            return {
                printLayer,
                fitBounds: src.includes('fitBounds'),
                noMaxZoom: !src.includes('maxZoom'),
                noModifyOriginal: !src.includes('layerpeta.setZoom'),
            };
        });

        expect(result.printLayer).toBe('tilelayer');
        expect(result.fitBounds).toBeTruthy();
        expect(result.noMaxZoom).toBeTruthy();
        expect(result.noModifyOriginal).toBeTruthy();
    });
});
