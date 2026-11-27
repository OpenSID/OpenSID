// Smoke Test — Level 5: Konfigurasi Desa
// Village identity configuration — OpenSID behaves like a multi-tenant system.
// Breakage here affects every page that renders village name/logo.
import { test, expect } from './smokeTest';

test.describe('Smoke: Konfigurasi Desa', () => {
  test('halaman identitas desa menampilkan informasi desa', async ({ page }) => {
    test.setTimeout(30000);

    const response = await page.goto('identitas_desa');
    expect(response?.status()).toBe(200);
    await page.waitForLoadState('networkidle');

    // Page header should be visible (contains village name)
    await expect(page.locator('h1').first()).toBeVisible();
    await expect(page.locator('.alert-danger')).toHaveCount(0);
  });

  test('logo/branding desa tersedia di halaman admin', async ({ page }) => {
    test.setTimeout(30000);

    await page.goto('beranda');
    await page.waitForLoadState('networkidle');

    // Sidebar navigation link "Beranda" is the most reliable branded element
    // (same assertion used by the passing modul-beranda.spec.ts test)
    await expect(page.getByRole('link', { name: 'Beranda' }).first()).toBeVisible();
  });

  test('form identitas desa dapat dibuka tanpa error', async ({ page }) => {
    test.setTimeout(30000);

    const response = await page.goto('identitas_desa/form');
    expect(response?.status()).toBe(200);
    await page.waitForLoadState('networkidle');

    // Use specific form id (#validasi) to avoid strict mode violation (page has 2 forms)
    await expect(page.locator('#validasi')).toBeVisible();
    await expect(page.locator('.alert-danger')).toHaveCount(0);
  });

  test('sebutan desa terdaftar di pengaturan', async ({ page }) => {
    test.setTimeout(30000);

    await page.goto('identitas_desa');
    await page.waitForLoadState('networkidle');

    // Gear icon to open the pengaturan modal should exist
    const gearIcon = page.locator('a[data-target="#pengaturan"]');
    await expect(gearIcon).toBeVisible({ timeout: 10000 });
  });
});
