import { test as setup, expect, chromium } from '@playwright/test';
import path from 'path';
import fs from 'fs';

const authDir = path.resolve(__dirname, './storage/auth');
const adminFile = path.join(authDir, 'admin.json');

/* Match the Playwright worker count (default 4 from playwright.config.ts Fix 4) */
const MAX_WORKER_AUTH = parseInt(process.env.PLAYWRIGHT_WORKERS || '4', 10);

async function loginAndSave(filePath: string): Promise<void> {
  const browser = await chromium.launch({
    args: ['--disable-gpu', '--disable-dev-shm-usage', '--no-sandbox'],
    /* Selaras dengan project 'chromium' di playwright.config.ts: Playwright tidak
       lagi menyediakan build Chromium untuk macOS 12 ke bawah, sehingga developer
       pada mesin tersebut memakai Chrome sistem lewat PLAYWRIGHT_CHANNEL=chrome.
       Dibiarkan kosong di CI agar tetap memakai Chromium bawaan Playwright. */
    ...(process.env.PLAYWRIGHT_CHANNEL ? { channel: process.env.PLAYWRIGHT_CHANNEL } : {}),
  });
  const context = await browser.newContext();
  const page = await context.newPage();
  await page.goto(`${process.env.PLAYWRIGHT_BASE_URL}/siteman`);
  await page.getByPlaceholder('Nama Pengguna').fill(process.env.PLAYWRIGHT_AUTH_USERNAME!);
  await page.getByPlaceholder('Kata sandi').fill(process.env.PLAYWRIGHT_AUTH_PASSWORD!);
  await page.getByRole('button', { name: 'Masuk' }).first().click();
  await expect(page.getByRole('heading', { name: 'Tentang OpenSID' })).toBeVisible({ timeout: 15000 });
  await context.storageState({ path: filePath });
  await browser.close();
}

setup('authenticate as admin', async ({ page }) => {
  await page.goto('siteman');
  await page.waitForTimeout(2000);
  await page.getByPlaceholder('Nama Pengguna').fill(process.env.PLAYWRIGHT_AUTH_USERNAME!);
  await page.getByPlaceholder('Kata sandi').fill(process.env.PLAYWRIGHT_AUTH_PASSWORD!);
  await page.getByRole('button', { name: 'Masuk' }).first().click();
  await expect(page.getByRole('heading', { name: 'Tentang OpenSID' })).toBeVisible();
  await page.context().storageState({ path: adminFile });
});

/* Fix 3: create one auth file per worker slot so parallel workers never share a CI3 session */
setup('create per-worker auth files', async ({}) => {
  /* Allow up to 30s per login × MAX workers */
  setup.setTimeout(MAX_WORKER_AUTH * 30_000);

  for (let i = 0; i < MAX_WORKER_AUTH; i++) {
    const workerFile = path.join(authDir, `worker-${i}.json`);
    await loginAndSave(workerFile);
    console.log(`  worker-${i}.json created`);
  }
});
