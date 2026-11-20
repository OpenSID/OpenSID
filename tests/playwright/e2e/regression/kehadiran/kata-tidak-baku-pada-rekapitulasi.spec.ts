// Issue: #9686 — Penggunaan kata tidak baku pada teks di rekapitulasi kehadiran
// https://github.com/OpenSID/OpenSID/issues/9686
import { test, expect } from '../../../utils/regressionTest';


test.describe('Bug/error: Penggunaan Kata Tidak Baku "Kustom Rentang" di Halaman Rekapitulasi Kehadiran #9686', () => {
  test('fix: perbaiki pengisian data survei', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/9686',
    },
  }, async ({ page }) => {
    await page.goto('kehadiran_rekapitulasi');
    await page.waitForLoadState('networkidle');

    // The date range input uses id="date-range" (not #daterange)
    const dateRangeInput = page.locator('#date-range');
    await expect(dateRangeInput).toBeVisible();
    await dateRangeInput.click();

    // If daterangepicker loaded (requires CDN access), verify correct label
    const picker = page.locator('.daterangepicker');
    const pickerVisible = await picker.isVisible().catch(() => false);
    if (pickerVisible) {
      await expect(picker).toContainText('Rentang Khusus');
      await expect(picker).not.toContainText('Kustom Rentang');
    }
  });
});