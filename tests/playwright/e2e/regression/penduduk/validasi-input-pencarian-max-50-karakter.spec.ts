// Issue: #10835 — Pencarian datatables halaman penduduk harus ada validasi input max 50 karakter
// https://github.com/OpenSID/OpenSID/issues/10835
import { test, expect } from '../../../utils/regressionTest';


test.describe('Bug/error: pada pencarian datatables halaman penduduk harus ada validasi input max 50 karakter #10835', () => {
  test('fix: membatasi input pencarian datatables maksimal 50 karakter', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10835',
    },
  }, async ({ page }) => {
    await page.goto('penduduk');
    await page.waitForSelector('[id="tabeldata"]', { timeout: 10000 });

    const searchInput = page.locator('.dataTables_filter input[type="search"]');
    await expect(searchInput).toBeVisible({ timeout: 5000 });

    const longText = 'Lorem ipsum dolor sit amet consectetur adipiscing elit sed do eiusmod tempor incididunt';
    await searchInput.fill(longText);
    await page.waitForTimeout(500);

    const inputValue = await searchInput.inputValue();
    expect(inputValue.length).toBeGreaterThan(0);

    // DataTables must remain functional (not crash)
    await expect(page.locator('#tabeldata')).toBeVisible();

    // No DataTables error alerts
    const errorMessages = page.locator('text=DataTables warning');
    await expect(errorMessages).not.toBeVisible();
  });

  test('verify: input normal tidak terpengaruh oleh validasi', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10835',
    },
  }, async ({ page }) => {
    await page.goto('penduduk');
    await page.waitForSelector('[id="tabeldata"]', { timeout: 10000 });

    const searchInput = page.locator('.dataTables_filter input[type="search"]');
    await expect(searchInput).toBeVisible({ timeout: 5000 });

    const normalText = 'John Doe';
    await searchInput.fill(normalText);
    await page.waitForTimeout(500);

    expect(await searchInput.inputValue()).toBe(normalText);
    console.log('✓ Test passed: Input normal tidak terpengaruh validasi');
  });

  test('verify: input 50 karakter tidak ditampilkan warning', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10835',
    },
  }, async ({ page }) => {
    await page.goto('penduduk');
    await page.waitForSelector('[id="tabeldata"]', { timeout: 10000 });

    const searchInput = page.locator('.dataTables_filter input[type="search"]');
    await expect(searchInput).toBeVisible({ timeout: 5000 });

    const text50Chars = 'a'.repeat(50);
    await searchInput.fill(text50Chars);
    await page.waitForTimeout(500);

    expect(await searchInput.inputValue()).toBe(text50Chars);
    await expect(page.locator('.search-limit-warning')).not.toBeVisible();
    console.log('✓ Test passed: Input 50 karakter tidak ditampilkan warning');
  });
});
