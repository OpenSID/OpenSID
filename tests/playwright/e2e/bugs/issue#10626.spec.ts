import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: urutkan dusun #10626', () => {
  test('fix: perbaiki fungsi ubah urutan dusun tidak berfungsi', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10626',
    },
  }, async ({ page }) => {
    await page.goto('wilayah');
    await page.locator('.padat > .fa').first().click();
    await page.getByRole('gridcell', { name: '' }).first().click();
    await page.getByRole('gridcell', { name: '' }).nth(3).click();
  });
});
