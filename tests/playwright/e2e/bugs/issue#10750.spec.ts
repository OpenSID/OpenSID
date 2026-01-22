import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Error tombol bantuan pada data anggota rtm #10750', () => {
  test('fix: perbaikan error setelah klik tombol bantuan', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10750',
    },
  }, async ({ page }) => {
    await page.goto('rtm/anggota/1');
    const page1Promise = page.waitForEvent('popup');
    await page.getByRole('link', { name: 'test bantuan rt' }).click();
    const page1 = await page1Promise;
    await expect(page1.getByText('Rincian Program', { exact: true })).toBeVisible();
  });
});
