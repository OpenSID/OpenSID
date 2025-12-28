import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Opsi pilihan RW muncul data dusun pada saat menambah penduduk & keluarga #10648', () => {
  test('fix: perbaikan tampilan dan urutan pilihan dusun rw rt', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10648',
    },
  }, async ({ page }) => {
    await page.goto('penduduk/form_peristiwa/1');
    await page.getByRole('textbox', { name: 'Pilih Dusun' }).click();
    await page.getByRole('treeitem', { name: 'LOCO' }).click();
    await page.getByRole('textbox', { name: 'Pilih RW' }).click();
    await expect(page.locator('span').filter({ hasText: 'Pilih RWDusun LOCO-Dusun' }).nth(1)).toBeVisible();
  });
});
