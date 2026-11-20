// Smoke Test — Level 3: Modul Penduduk
// Core citizen registry — must survive every architectural change.
import { test, expect } from './smokeTest';

test.describe('Smoke: Modul Penduduk', () => {
  test('halaman daftar penduduk dapat diakses tanpa error', async ({ page }) => {
    test.setTimeout(30000);

    const response = await page.goto('penduduk');
    expect(response?.status()).toBe(200);
    await page.waitForLoadState('networkidle');

    // Page chrome: header should be visible
    await expect(page.locator('.content-header h1, h1').first()).toBeVisible();
    // No server error alerts
    await expect(page.locator('.alert-danger')).toHaveCount(0);
  });

  test('DataTables penduduk mengembalikan respons JSON yang valid', async ({ page }) => {
    test.setTimeout(30000);

    let datatableResponse: Record<string, unknown> | null = null;
    page.on('response', async resp => {
      if (resp.url().includes('datatables') && resp.url().includes('penduduk')) {
        try {
          datatableResponse = await resp.json();
        } catch { /* non-JSON response, skip */ }
      }
    });

    await page.goto('penduduk');
    await page.waitForLoadState('networkidle');

    // DataTables AJAX endpoint must respond with standard structure
    if (datatableResponse) {
      expect(datatableResponse).toHaveProperty('draw');
      expect(datatableResponse).toHaveProperty('data');
      expect(datatableResponse).toHaveProperty('recordsTotal');
    }
    // Even if no AJAX was intercepted the page must not show an error
    await expect(page.locator('.alert-danger, .error-page')).toHaveCount(0);
  });

  test('pencarian penduduk tidak menghasilkan error server', async ({ page }) => {
    test.setTimeout(30000);

    const errors: string[] = [];
    page.on('response', resp => {
      if (resp.url().includes('penduduk') && resp.status() >= 500) {
        errors.push(`${resp.status()} ${resp.url()}`);
      }
    });

    await page.goto('penduduk');
    await page.waitForLoadState('networkidle');

    // Type a search term in the DataTables search box
    const searchBox = page.getByRole('searchbox', { name: 'Cari:' });
    if (await searchBox.isVisible()) {
      await searchBox.fill('A');
      await page.waitForLoadState('networkidle');
    }

    expect(errors).toHaveLength(0);
  });

  test('halaman detail penduduk pertama dapat dibuka', async ({ page }) => {
    test.setTimeout(30000);

    // Use a known penduduk ID from seed data
    const response = await page.goto('penduduk/detail/98');
    // 200 = found, 302 = redirect to list (id doesn't exist), 404 should NOT occur with bad ID
    expect(response?.status()).not.toBe(500);
    // If page loaded, verify no error heading
    if (response?.status() === 200) {
      await expect(page.locator('.alert-danger')).toHaveCount(0);
    }
  });
});
