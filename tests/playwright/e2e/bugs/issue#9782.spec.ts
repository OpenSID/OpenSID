import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Nama Inputan sama #9782', () => {
  test('fix: perbaiki nama inputan sama pada form galeri', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/9782',
    },
  }, async ({ page }) => {
    await page.goto('gallery');
    await expect(page.getByRole('link', { name: ' Tambah' })).toBeVisible();
    await page.getByRole('link', { name: ' Tambah' }).click();
    await expect(page.getByRole('heading', { name: 'Pengaturan Rincian Album' })).toBeVisible();
    await expect(page.getByText('Nama Gambar')).toBeVisible();
  });
});