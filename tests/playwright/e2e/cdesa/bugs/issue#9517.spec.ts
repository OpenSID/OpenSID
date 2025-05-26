import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../../storage/auth/admin.json'),
});

test.describe('Klik Batal Menghilangkan Pilihan Pada Kolom Peta #9517', () => {
  test('fix: perbaiki ketika klik batal menghilangkan pilihan pada kolom peta', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/9517',
    },
  }, async ({ page }) => {
    const response = await page.goto('cdesa/mutasi/1/form/1/1');

    if (response && response.status() === 200) {
        await expect(page.getByText('Pilih Area', { exact: true })).toBeVisible();
        await expect(page.getByRole('combobox', { name: 'Area' })).toBeVisible();
        await page.getByText('Buat Area').click();
        await page.getByRole('textbox', { name: 'Area' }).click();
        await page.getByRole('treeitem', { name: 'Area 2' }).click();
        await page.getByRole('button', { name: ' Batal' }).click();
        await expect(page.getByText('Pilih Area', { exact: true })).toBeVisible();
        await expect(page.getByRole('combobox', { name: 'Area' })).toBeVisible();
    }
  });
});
