/**
 * Regression-test fixture: each test gets its own fresh CI3 session.
 *
 * Regression tests mutate shared state (delete rows, change statuses, upload
 * files). Running them in parallel with a shared per-worker session causes
 * conflicts: one test's side-effect breaks another test's preconditions.
 *
 * By logging in fresh per TEST we get an independent session for every test,
 * which eliminates CI3 session-hash staleness and cross-test data conflicts.
 */
import { test as base, expect } from '@playwright/test';
import type { BrowserContext } from '@playwright/test';

const test = base.extend<{ context: BrowserContext }>({
  context: async ({ browser }, use) => {
    const context = await browser.newContext();
    const page = await context.newPage();

    await page.goto(`${process.env.PLAYWRIGHT_BASE_URL}/siteman`);
    await page.getByPlaceholder('Nama Pengguna').fill(process.env.PLAYWRIGHT_AUTH_USERNAME!);
    await page.getByPlaceholder('Kata sandi').fill(process.env.PLAYWRIGHT_AUTH_PASSWORD!);
    await page.getByRole('button', { name: 'Masuk' }).first().click();
    await expect(page.getByRole('heading', { name: 'Tentang OpenSID' })).toBeVisible({ timeout: 15000 });

    // Tutup halaman login agar tidak membuat request background (notifikasi dll)
    // yang dapat menyebabkan session CI3 di-regenerate dan menginvalidasi sesi Page B.
    await page.close();

    await use(context);
    await context.close();
  },
});

export { test, expect };
export * from '@playwright/test';
