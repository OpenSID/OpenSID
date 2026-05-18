import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Pengguna masih bisa login kehadiran, padahal status pengguna sedang tidak aktif #11223', () => {
  test('fix: perbaiki login kehadiran dengan status pengguna tidak aktif', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11223',
    },
  }, async ({ page }) => {
    // akses ke halaman
    await page.goto('kehadiran/masuk');

    await page.getByText('Terima semua cookie').nth(1).click();

    await page.getByRole('textbox', { name: 'Username / NIK' }).fill('test');
    await page.getByRole('textbox', { name: 'Password' }).fill('test');
    await page.getByRole('button', { name: 'MASUK' }).click();
  });
});