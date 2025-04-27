import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../../storage/auth/admin.json'),
});

test.describe('NIK Tidak Tampil #4824', () => {
  test('fix: perbaikan NIK tidak tampil', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/4824',
    },
  }, async ({ page }) => {
    await page.goto('cdesa');
    await page.getByRole('link', { name: '', exact: true }).click();
    await expect(page.getByRole('textbox', { name: 'NIK Pemilik' })).not.toHaveValue('');
  });
});
