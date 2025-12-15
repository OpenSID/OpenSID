import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Ubah Sub Kategori pada titik tekunci #10615', () => {
  test('fix: perbaiki ubah sub kategori point status nya menjadi tidak aktif', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10615',
    },
  }, async ({ page }) => {
    await expect(page.getByRole('gridcell', { name: 'Ya' })).toBeVisible();
    await page.getByRole('link', { name: '' }).click();
    await page.getByText('Simpan').click();
    await expect(page.getByRole('gridcell', { name: 'Ya' })).toBeVisible();
  });
});
