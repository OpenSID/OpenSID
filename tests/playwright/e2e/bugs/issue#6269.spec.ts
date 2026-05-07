import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Security: Penambahan validasi tujuan URL pada fitur RSS Feed (link_feed) untuk mencegah Blind SSRF #6269', () => {
  test('fix: perbaiki validasi input link_feed blacklisted', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/premium/issues/6269',
    },
  }, async ({ page }) => {
    await page.goto('setting_web');

    await page.getByRole('link', { name: 'Pengaturan', exact: true }).click();
    await page.getByText('Ya', { exact: true }).click();
    await page.locator('#link_feed').click();
    await page.locator('#link_feed').fill('http://127.0.0.1');
    await page.getByRole('button', { name: ' Simpan' }).click();
    await expect(page.getByText('Domain lokal tidak diizinkan.')).toBeVisible();
  });
});
