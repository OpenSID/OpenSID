import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Umur anak pada form edit KIA #10789', () => {
  test('fix: perbaikan umur pada edit pemantauan anak', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10789',
    },
  }, async ({ page }) => {
    await page.goto('stunting/pemantauan_anak');
    await page.getByRole('cell', { name: 'Maret 2025' }).click();
    await page.getByRole('link', { name: '' }).click();
    await expect(page.locator('input[name="umur"]')).toHaveValue('0 tahun 10 bulan');
  });
});
