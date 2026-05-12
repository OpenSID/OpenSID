import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: error akses pengaturan pengguna', () => {
  test('fix: perbaiki error akses pengaturan pengguna', {
  }, async ({ page }) => {
    await page.goto('man_user');

    await expect(page.getByRole('textbox', { name: 'Aktif' })).toBeVisible();
  });
});