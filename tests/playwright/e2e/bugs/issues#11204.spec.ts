import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Pilihan kategori tidak muncul pada saat menambahkan data Lokasi, Garis dan Area pada sub menu pengaturan peta #11204', () => {
  test('fix: perbaiki error saat memilih kategori lokasi', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11204',
    },
  }, async ({ page }) => {
    await page.goto('area/form/0/1');

    await page.getByRole('textbox', { name: 'Pilih Jenis' }).click();
    await page.getByRole('treeitem', { name: 'jalur selokan' }).click();
    await expect(page.getByRole('textbox', { name: 'Tidak ada kategori' })).toBeVisible();
  });

  test('fix: perbaiki error saat memilih kategori garis', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11204',
    },
  }, async ({ page }) => {
    await page.goto('garis/form/0');

    await page.getByRole('textbox', { name: 'Pilih Jenis' }).click();
    await page.getByRole('treeitem', { name: 'Jalan setapak' }).click();
    await expect(page.getByRole('textbox', { name: 'Tidak ada kategori' })).toBeVisible();
  });
});