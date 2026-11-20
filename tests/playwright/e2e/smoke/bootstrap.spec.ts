// Smoke Test — Level 1: Bootstrap Aplikasi
// Verifies the app boots without fatal errors after architectural changes.
import { test, expect } from '@playwright/test';

test.describe('Smoke: Bootstrap Aplikasi', () => {
  test('halaman login dapat diakses tanpa error', async ({ page }) => {
    const response = await page.goto('siteman');
    expect(response?.status()).toBeLessThan(500);
    await expect(page.getByPlaceholder('Nama Pengguna')).toBeVisible();
    await expect(page.getByPlaceholder('Kata sandi')).toBeVisible();
    await expect(page.getByRole('button', { name: 'Masuk', exact: true })).toBeVisible();
  });

  test('root URL tidak mengembalikan error 500', async ({ page }) => {
    const response = await page.goto('/');
    // Redirect to login (302) or direct 200 are both acceptable
    expect(response?.status()).not.toBe(500);
    expect(response?.status()).not.toBe(404);
  });

  test('aset statis CSS termuat pada halaman login', async ({ page }) => {
    // Collect 4xx/5xx asset load failures
    const assetErrors: string[] = [];
    page.on('response', resp => {
      if (resp.url().match(/\.(css|js)(\?|$)/) && resp.status() >= 400) {
        assetErrors.push(`${resp.status()} ${resp.url()}`);
      }
    });

    await page.goto('siteman');
    await page.waitForLoadState('networkidle');

    // Login form should be visually styled (not a bare unstyled page)
    const loginBox = page.locator('.login-box, .login-page, form');
    await expect(loginBox.first()).toBeVisible();

    expect(assetErrors).toHaveLength(0);
  });
});
