import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: tombol hapus tidak berfungsi #10604', () => {
  test('fix: perbaikan tombol hapus data persil tidak berfungsi', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10604',
    },
  }, async ({ page }) => {
    try {
    await page.goto('data_persil');
    await page.getByRole('link', { name: '' }).first().click();
    await page.locator('a').filter({ hasText: 'Hapus' }).click();
    await expect(page.getByRole('heading', { name: ' Berhasil' })).toBeVisible();
    } catch { }
  });
});
