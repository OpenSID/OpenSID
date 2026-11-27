/**
 * Smoke-test fixture: each test gets its own fresh CI3 session.
 *
 * Why this instead of workerTest:
 * The full-suite workerTest assigns one session file per WORKER process. When
 * the admin user logs in N times during setup (once per worker file), the DB
 * column `user.session` is updated to the LAST login's hash. Earlier worker
 * files end up with a stale `sesi` value. Most routes ignore this field, but
 * the `beranda` dashboard validates it strictly and redirects to /siteman.
 *
 * By logging in fresh per TEST we get a unique, always-current session ID and
 * `sesi` hash for every test — no cross-test contamination, no stale hashes.
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

    await use(context);
    await context.close();
  },
});

export { test, expect };
export * from '@playwright/test';
