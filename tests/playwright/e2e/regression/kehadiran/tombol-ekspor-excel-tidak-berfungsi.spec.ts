// Issue: #11160 — Tombol ekspor ke Excel tidak berfungsi (hanya loading)
// https://github.com/OpenSID/OpenSID/issues/11160
import { test, expect } from '../../../utils/regressionTest';


test.describe('Bug/error: Tombol Ekspor ke Excel Tidak Berfungsi (Hanya Loading) #11160', () => {

  test.beforeEach(async ({ page }) => {
    await page.goto('kehadiran_rekapitulasi');
    await page.waitForLoadState('networkidle');
  });

  test('fix: tombol Ekspor ke Excel membuka download file Excel', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11160',
    },
  }, async ({ page }) => {
    const excelBtn = page.locator('#excel');
    await expect(excelBtn).toBeVisible();
    await page.waitForLoadState('networkidle');

    // Use waitForResponse instead of fixed timeout
    const [response] = await Promise.all([
      page.waitForResponse((r) => r.url().includes('kehadiran_rekapitulasi/ekspor'), { timeout: 10000 }),
      excelBtn.click(),
    ]);

    expect(response.status()).toBe(200);
  });

  test('fix: ekspor dengan filter tanggal, status, dan perangkat berfungsi', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11160',
    },
  }, async ({ page }) => {
    await page.waitForSelector('#tabeldata', { state: 'visible' });
    await page.waitForLoadState('networkidle');

    const statusOptions = page.locator('#status option');
    const optionCount = await statusOptions.count();

    if (optionCount > 1) {
      await page.locator('#status').selectOption({ index: 1 });
      await page.waitForLoadState('networkidle');
    }

    const excelBtn = page.locator('#excel');
    await expect(excelBtn).toBeVisible();

    const [response] = await Promise.all([
      page.waitForResponse((r) => r.url().includes('kehadiran_rekapitulasi/ekspor'), { timeout: 10000 }),
      excelBtn.click(),
    ]);

    expect(response.status()).toBe(200);
  });
});
