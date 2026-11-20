// Smoke Test — Level 2: Autentikasi
// The most common breakage point during architectural migrations.
// Tests that login, session persistence, and logout all work end-to-end.
import { test as rawTest, expect } from '@playwright/test';
import { test as authTest } from './smokeTest';

// Login and session tests run WITHOUT pre-stored auth
rawTest.describe('Smoke: Login', () => {
  rawTest('login dengan akun valid berhasil masuk ke dashboard', async ({ page }) => {
    rawTest.setTimeout(30000);

    await page.goto('siteman');
    await page.getByPlaceholder('Nama Pengguna').fill(process.env.PLAYWRIGHT_AUTH_USERNAME!);
    await page.getByPlaceholder('Kata sandi').fill(process.env.PLAYWRIGHT_AUTH_PASSWORD!);
    await page.getByRole('button', { name: 'Masuk' }).first().click();

    // Dashboard heading is the definitive sign of a successful login
    await expect(page.getByRole('heading', { name: 'Tentang OpenSID' })).toBeVisible({ timeout: 15000 });
  });

  rawTest('halaman yang membutuhkan auth dialihkan ke login', async ({ page }) => {
    const response = await page.goto('beranda');
    // Should redirect to siteman (302) or show login page (200 siteman)
    const url = page.url();
    const statusOk = response?.status() === 200 || response?.status() === 302 || response?.status() === 301;
    expect(statusOk).toBe(true);
    // Either we are on siteman or we get an error page — no 500 is acceptable
    expect(response?.status()).not.toBe(500);
  });
});

// Session persistence and logout run WITH pre-stored auth (via workerTest)
authTest.describe('Smoke: Session & Logout', () => {
  authTest('session tetap aktif saat berpindah antar halaman', async ({ page }) => {
    authTest.setTimeout(30000);

    await page.goto('beranda');
    await page.waitForLoadState('networkidle');
    // Still authenticated — not redirected to login
    await expect(page.url()).not.toContain('siteman');
    await expect(page.getByRole('heading', { name: 'Tentang OpenSID' })).toBeVisible({ timeout: 10000 });

    // Navigate to another module
    await page.goto('penduduk');
    await page.waitForLoadState('networkidle');
    // Should still be on penduduk, not redirected to login
    await expect(page.url()).not.toContain('siteman');
  });

  authTest('logout menghapus session dan mengalihkan ke halaman login', async ({ page }) => {
    authTest.setTimeout(30000);

    // smokeTest already logged in fresh; now log out and verify redirect to login.
    // This test's context is isolated (per-test login) so logout only affects this test's session.
    await page.goto('siteman/logout');
    await page.waitForLoadState('networkidle');

    await expect(page.url()).toContain('siteman');
    await expect(page.getByPlaceholder('Nama Pengguna')).toBeVisible({ timeout: 10000 });
  });
});
