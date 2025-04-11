
import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../../storage/auth/admin.json'),
});

test.describe('Form Teks Berjalan Link Internal #9433', () => {
  test('fix: form teks terjalan link internal tidak tersimpan', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/9433',
    },
  }, async ({ page }) => {
    await page.goto('teks_berjalan');

    await page.getByRole('link', { name: ' Tambah' }).click();
    await page.getByRole('textbox', { name: 'Isi teks berjalan' }).click();
    await page.getByRole('textbox', { name: 'Isi teks berjalan' }).fill('test teks berjalan');
    await page.getByRole('textbox', { name: '-- Cari Judul Artikel --' }).click();
    await page.getByRole('treeitem', { name: 'Agustus 2016 | Wilayah Desa' }).click();
    await page.getByRole('textbox', { name: 'Judul tautan ke artikel atau url' }).click();
    await page.getByRole('textbox', { name: 'Judul tautan ke artikel atau url' }).fill('judul artikel');
    await page.getByRole('button', { name: 'Simpan' }).click();
    await expect(page.locator('#dragable')).toContainText('26 Agustus 2016 Wilayah Desa');
  });
});
