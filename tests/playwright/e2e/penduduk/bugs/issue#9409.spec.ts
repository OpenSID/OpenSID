import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../../storage/auth/admin.json'),
});

test.describe('Tombol simpan tidak berfungsi setelah klik batal pada form ubah biodata penduduk #9409', () => {
  test('fix: perbaikan fungsi simpan penduduk', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/9409'
    }
  }, async ({ page }) => {
    await page.goto('penduduk/form/98');

    await page.getByRole('button', { name: ' Batal' }).click();
    await page.getByRole('button', { name: ' Simpan' }).click();

    await expect(page.getByText('Data Penduduk', { exact: true })).toBeVisible();
  });
});
