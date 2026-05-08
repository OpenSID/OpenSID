import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: preview cetak pada laporan bulanan tidak ada garisnya #11153', () => {
  test('fix: perbaiki preview cetak pada laporan bulanan tidak ada garis', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11153',
    },
  }, async ({ page }) => {
    await page.goto('laporan');

    await page.getByRole('link', { name: ' Cetak' }).click();
    const page1Promise = page.waitForEvent('popup');
    await page.locator('#validasi').getByText('Cetak', { exact: true }).click();
    const page1 = await page1Promise;
    await expect(page1.locator('iframe[name="print-frame"]').contentFrame().getByText('LAMPIRAN A-')).toBeVisible();
  });
});