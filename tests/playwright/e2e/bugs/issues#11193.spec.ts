import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: error saat memilih kategori area #11193', () => {
  test('fix: perbaiki error saat memilih kategori peta', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11193',
    },
  }, async ({ page }) => {
    await page.goto('area/form/0/1');

    await page.getByRole('textbox', { name: 'Pilih Jenis' }).click();
    await page.getByRole('treeitem', { name: 'jalur selokan' }).click();
    await expect(page.getByRole('textbox', { name: 'Tidak ada kategori' })).toBeVisible();
  });
});