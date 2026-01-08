import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Fungsi Filter Tidak Aktif di Buku Administrasi Umum - Buku Lembaran Desa Dan Berita Desa tidak berfungsi #10695', () => {
  test('fix: perbaikan filter tidak aktif tidak berfungsi', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10695',
    },
  }, async ({ page }) => {
    await page.goto('lembaran_desa');
    await page.getByRole('link', { name: '' }).click();
    await page.getByRole('combobox', { name: 'Pilih Status' }).locator('b').click();
    await page.getByRole('treeitem', { name: 'Tidak Aktif' }).click();
    await expect(page.getByRole('gridcell', { name: 'RPJMDes Miau Merah Tahun 2016' })).toBeVisible();
    await page.getByRole('link', { name: '' }).nth(1).click();
  });
});
