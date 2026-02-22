import { test as setup, expect } from '@playwright/test';
import path from 'path';

const adminFile = path.resolve(__dirname, './storage/auth/admin.json');

setup('authenticate as admin', async ({ page }) => {
  const secretCode = process.env.PLAYWRIGHT_SECRET_CODE ?? '';
  if (!secretCode) {
    throw new Error('PLAYWRIGHT_SECRET_CODE belum diisi di .e2e.env.');
  }

  await page.goto('siteman');
  await page.waitForTimeout(2000);
  await page.getByPlaceholder('Nama Pengguna').fill(process.env.PLAYWRIGHT_AUTH_USERNAME!);
  await page.getByPlaceholder('Kata sandi').fill(process.env.PLAYWRIGHT_AUTH_PASSWORD!);
  await page.locator('form#validasi').evaluate((form, code) => {
    const secretInput = document.createElement('input');
    secretInput.type = 'hidden';
    secretInput.name = 'secret_code';
    secretInput.value = code as string;
    form.appendChild(secretInput);
  }, secretCode);

  const captchaInput = page.locator('input[name="captcha_code"]');
  if (await captchaInput.count()) {
    await captchaInput.fill('123456');
  }

  await page.getByRole('button', { name: 'Masuk' }).click();
  await expect(page).toHaveURL(/(main|beranda)/);

  await page.context().storageState({ path: adminFile });
});
