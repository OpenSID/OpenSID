import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: gagal simpan data survei #9676', () => {
  test('fix: perbaiki pengisian data survei', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/9676',
    },
  }, async ({ page }) => {
    await page.goto('analisis_respon/2');
    await page.getByRole('row', { name: '1  0720110200700002 SUDIRANA' }).getByRole('link').click();
    await page.getByRole('textbox', { name: 'milik orang tua' }).click();
    await page.getByRole('treeitem', { name: 'milik sendiri' }).click();
    await page.getByRole('textbox', { name: '2. Rp 500.000,- sampa Rp 1.' }).click();
    await page.getByRole('treeitem', { name: 'diatas Rp 2.000.000,-' }).click();
    await page.getByRole('button', { name: ' Simpan' }).click();
    await expect(page.getByText('Berhasil Simpan Data Kuisioner')).toBeVisible();
  });
});