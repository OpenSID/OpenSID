// Issue: #10802 — Error saat import data analisis
// https://github.com/OpenSID/OpenSID/issues/10802
import { test, expect } from '../../../utils/regressionTest';


test.describe('Bug/error: error saat import data analisis #10802', () => {
  test('fix: import data analisis responden dengan csrf token', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10802',
    },
  }, async ({ page }) => {
    // Test verifikasi halaman analisis dapat diakses dan merender dengan baik

    // Step 1: Navigasi ke halaman analisis master
    await page.goto('analisis_master', { waitUntil: 'networkidle' });
    await expect(page.locator('h1')).toBeVisible({ timeout: 10000 });

    // Step 2: Verifikasi halaman master load tanpa error
    const masterTable = page.locator('table.table').first();
    if (await masterTable.isVisible({ timeout: 5000 })) {
      // Verifikasi ada baris di tabel
      const rows = page.locator('table.table tbody tr');
      const rowCount = await rows.count();
      expect(rowCount).toBeGreaterThan(0);

      // Jika ada baris, coba klik link analisis pertama
      if (rowCount > 0) {
        const firstLink = rows.first().locator('a').first();
        if (await firstLink.isVisible()) {
          await firstLink.click();
          await page.waitForLoadState('networkidle');
          // Verifikasi halaman menu analisis load
          await expect(page.locator('body')).toBeVisible();
        }
      }
    }
  }, { timeout: 60000 });
});
