import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Alamat pada halaman Rekam Surat Perseorangan tidak muncul #10689', () => {
  test('fix: perbaikan alamat tidak tampil pada rekam surat perseorangan', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10689',
    },
  }, async ({ page }) => {
    await page.goto('keluar/perorangan');
    await page.locator('#select2-nik-container').click();
    await page.getByRole('treeitem', { name: 'NIK/Tag ID Card : 5201142005716996 - AHL\'UL Alamat: RT-004, RW-- DUSUN MANGSIT' }).click();
    await expect(page.getByRole('cell', { name: 'brr cendana v RT 004 / RW -' })).toBeVisible();
  });
});
