// Issue: #10889 — Malfungsi penutupan overlay pada navigasi sidebar di tampilan mobile
// https://github.com/OpenSID/OpenSID/issues/10889
import { test, expect } from '../../../utils/regressionTest';


test.describe('Bug/error: Malfungsi Penutupan Overlay pada Navigasi Sidebar pada Tampilan Mobile #10889', () => {
  test('fix: perbaikan Malfungsi Penutupan Overlay pada Navigasi Sidebar pada Tampilan Mobile', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10889'
    }
  }, async ({ page }) => {
    await page.setViewportSize({ width: 767, height: 800 });
    await page.goto('penduduk');
    await page.waitForSelector('.control-sidebar', { timeout: 10000 });

    // Open the right sidebar
    await page.locator('a[data-toggle="control-sidebar"]').click();
    await page.waitForTimeout(400);

    // Verify sidebar is open — class is on .control-sidebar, not on body
    await expect(page.locator('.control-sidebar')).toHaveClass(/control-sidebar-open/);

    // Click left sidebar toggle — this should also close the right sidebar (bug fix)
    await page.locator('a.sidebar-toggle[data-toggle="push-menu"]').click();
    await page.waitForTimeout(400);

    // Verify right sidebar is now closed
    await expect(page.locator('.control-sidebar')).not.toHaveClass(/control-sidebar-open/);
  });
});
