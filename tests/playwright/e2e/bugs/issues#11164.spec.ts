import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Ubah Tanggal Pemantauan #11164', () => {
  test('fix: perbaiki tanggal pemantauan bulanan anak', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11164',
    },
  }, async ({ page }) => {
    await page.goto('stunting/pemantauan_anak');

    await page.getByRole('link', { name: ' Tambah' }).click();

    await page.getByRole('textbox', { name: 'Masukkan tanggal periksa' }).click();
    await expect(page.getByRole('columnheader', { name: 'Sn' })).toBeVisible();
  });
});