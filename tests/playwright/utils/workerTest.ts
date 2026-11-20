/**
 * Fix 3: per-worker auth fixture.
 *
 * Overrides the built-in `context` fixture so every worker uses its own
 * pre-created session file (worker-N.json) instead of the shared admin.json.
 * This prevents CI3 DB-session rows from being overwritten across workers.
 *
 * Setup creates the files: `npx playwright test --project=setup`
 * Falls back to admin.json if worker-N.json does not exist yet.
 */
import { test as base, expect } from '@playwright/test';
import type { BrowserContext } from '@playwright/test';
import path from 'path';
import fs from 'fs';

const authDir = path.resolve(__dirname, '../storage/auth');

const test = base.extend<{ context: BrowserContext }, { workerStorageState: string }>({
  /* Worker-scoped: resolved once per worker process, not once per test */
  workerStorageState: [async ({}, use, workerInfo) => {
    const workerFile = path.join(authDir, `worker-${workerInfo.workerIndex}.json`);
    const fallback = path.join(authDir, 'admin.json');

    if (!fs.existsSync(workerFile)) {
      if (fs.existsSync(fallback)) {
        fs.copyFileSync(fallback, workerFile);
      }
    }

    await use(fs.existsSync(workerFile) ? workerFile : fallback);
  }, { scope: 'worker' }],

  /* Test-scoped override: each test gets a fresh page in a context with the worker's own session */
  context: async ({ browser, workerStorageState }, use) => {
    const context = await browser.newContext({ storageState: workerStorageState });
    await use(context);
    await context.close();
  },
});

export { test, expect };
export * from '@playwright/test';
