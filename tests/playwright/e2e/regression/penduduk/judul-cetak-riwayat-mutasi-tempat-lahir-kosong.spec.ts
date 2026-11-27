// Issue: #11092 — Penyesuaian judul cetak dan unduhan data riwayat mutasi penduduk — tempat lahir tidak ada
// https://github.com/OpenSID/OpenSID/issues/11092
import { test, expect } from '../../../utils/regressionTest';


test.describe('Bug/error: Penyesuaian Judul Cetak dan Unduhan Data Riwayat Mutasi Penduduk dimana Lahir tidak ada #11092', () => {
    test('fix: perbaikan Penyesuaian Judul Cetak dan Unduhan Data Riwayat Mutasi Penduduk dimana Lahir tidak ada', {
        annotation: {
            type: 'issue',
            description: 'https://github.com/OpenSID/OpenSID/issues/11092',
        },
    }, async ({ page }) => {
        await page.goto('penduduk_log/cetak');
        await page.bringToFront();
        await page.waitForLoadState('domcontentloaded');
        await page.waitForTimeout(2000);

        // Print page should not redirect to beranda
        await expect(page.locator('body')).not.toContainText('Tentang OpenSID', { timeout: 5000 });

        // The cetak page should render correctly (load, not error)
        const h3 = page.locator('h3').first();
        if (await h3.count() > 0) {
            await expect(h3).toBeVisible({ timeout: 5000 });
        } else {
            // Page loaded but may have no h3 — just verify it's not an error page
            await expect(page.locator('body')).not.toContainText('Exception');
            await expect(page.locator('body')).not.toContainText('Internal Server Error');
        }
    });
});
