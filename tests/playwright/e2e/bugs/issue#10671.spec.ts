import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: data kesukuan (adat dan marga) tidak tersimpan ketika menambah keluarga dari menu keluarga #10671', () => {
  test('fix: perbaiki form data adat tidak tampil di keluarga', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10671',
    },
  }, async ({ page }) => {
    await page.goto('keluarga/form');
    await page.locator('#select2-adat-container').click();
    await page.locator('input[type="search"]').fill('Apakah Adat');
    await expect(page.getByRole('treeitem', { name: 'Apakah Adat' })).toBeVisible();
  });
});
