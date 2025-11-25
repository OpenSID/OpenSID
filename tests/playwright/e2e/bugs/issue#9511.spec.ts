import { test, expect} from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Duplikasi teks pada dropdown "Pilih Kegunaan" form Inventaris tanah #9511', () => {
  test('fix: perbaiki duplikasi teks pada dropdown Pilih Kegunaan', {
    annotation: {
        type: 'issue',
        description: 'https://github.com/OpenSID/OpenSID/issues/9511'
    }
  }, async ({ page }) => {
    await page.goto('inventaris_tanah/form');

    await expect(page.getByLabel('Penggunaan', { exact: true })).toBeVisible();
    const incorrectOption = page.locator('option:has-text("-- Pilih Pilih Kegunaan --")');
    await expect(incorrectOption).toHaveCount(0);
  });
});