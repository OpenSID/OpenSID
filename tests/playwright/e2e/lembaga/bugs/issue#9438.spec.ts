import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../../storage/auth/admin.json'),
});

test.describe('Perbaiki simbol dan tidak muncul #9438', () => {
  test('fix: perbaiki simbol dan tidak muncul', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/9438',
    },
  }, async ({ page }) => {
    await page.goto('lembaga_master');
    await page.getByRole('link', { name: ' Tambah' }).click();
    await page.getByRole('textbox', { name: 'Kategori Lembaga' }).click();
    await page.getByRole('textbox', { name: 'Kategori Lembaga' }).fill('lembaga &');
    await page.getByRole('textbox', { name: 'Deskripsi Lembaga' }).click();
    await page.getByRole('textbox', { name: 'Deskripsi Lembaga' }).fill('deskripsi lembaga &');
    await page.getByRole('button', { name: ' Simpan' }).click();
    await expect(page.locator('tbody')).toContainText('lembaga &');
    await expect(page.locator('tbody')).toContainText('deskripsi lembaga &');
  });
});
