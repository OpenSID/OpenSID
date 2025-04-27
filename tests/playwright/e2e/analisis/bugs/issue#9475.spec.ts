import { test, expect} from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../../storage/auth/admin.json'),
});

test.describe('Status analisis otomatis nonaktif setelah ubah data #9475', () => {
  test('fix: perbaiki status analisis otomatis nonaktif setelah ubah data', {
    annotation: {
        type: 'issue',
        description: 'https://github.com/OpenSID/OpenSID/issues/9475'
    }
  }, async ({ page }) => {
    await page.goto('analisis_master');

    await expect(page.getByRole('link', { name: '' }).first()).toBeVisible();
    await page.getByRole('link', { name: '' }).first().click();
    await page.getByRole('button', { name: ' Simpan' }).click();
    await expect(page.getByRole('link', { name: '' }).first()).toBeVisible();
  });
});