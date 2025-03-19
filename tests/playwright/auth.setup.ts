import { test as setup, expect } from '@playwright/test';
import path from 'path';

const adminFile = path.resolve(__dirname, './storage/auth/admin.json');

setup('authenticate as admin', async ({ page }) => {

  await page.goto('siteman');
  await page.waitForTimeout(2000);
  await page.getByPlaceholder('Nama Pengguna').fill(process.env.PLAYWRIGHT_AUTH_USERNAME!);
  await page.getByPlaceholder('Kata sandi').fill(process.env.PLAYWRIGHT_AUTH_PASSWORD!);
  await page.getByRole('button', { name: 'Masuk' }).click();
  await expect(page.getByRole('heading', { name: 'Tentang OpenSID' })).toBeVisible();

  await page.context().storageState({ path: adminFile });
});
