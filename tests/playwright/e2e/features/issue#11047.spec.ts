import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: lihat password pada halaman pengaturan aplikasi tidak berfungsi #11047', () => {
  test('fix: tombol lihat password pada email SMTP dapat toggle password <-> text', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11047',
    },
  }, async ({ page }) => {
    await page.goto('setting');
    await expect(page.locator('#validasi')).toBeVisible();

    const emailNotifSelect = page.locator('#email_notifikasi');
    await emailNotifSelect.selectOption('1');

    const passwordContainer = page.locator('#form_email_smtp_pass');
    const passwordInput = page.locator('#input_email_smtp_pass');
    const toggleButton = passwordContainer.locator('.show-hide-password').first();
    const toggleIcon = toggleButton.locator('i').first();

    await expect(passwordContainer).toBeVisible();
    await expect(passwordInput).toBeVisible();
    await expect(passwordInput).toHaveAttribute('type', 'password');
    await expect(toggleButton).toBeVisible();
    await expect(toggleIcon).toHaveClass(/fa-eye-slash/);

    await toggleButton.click();
    await expect(passwordInput).toHaveAttribute('type', 'text');
    await expect(toggleIcon).toHaveClass(/fa-eye/);

    await toggleButton.click();
    await expect(passwordInput).toHaveAttribute('type', 'password');
    await expect(toggleIcon).toHaveClass(/fa-eye-slash/);
  });
});
