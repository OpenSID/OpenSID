import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Gagal ketika Hapus Data Pengguna terpilih #11240', () => {
  test('fix: perbaiki gagal ketika hapus data pengguna terpilih', {
  }, async ({ page }) => {
    await page.goto('man_user');

    await page.locator('#checkall').check();
    await page.getByRole('button', { name: ' Hapus' }).click();
    await page.getByRole('button', { name: 'Ya, Hapus' }).click();
    await expect(page.getByRole('heading', { name: ' Gagal' })).toBeVisible();
  });
});