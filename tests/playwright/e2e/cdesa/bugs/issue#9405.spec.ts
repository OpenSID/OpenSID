import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../../storage/auth/admin.json'),
});

test.describe('Fungsi tombol batal pada ubah data C-Desa #9405', () => {
  test('fix: perbaikan tombol batal kembali ke tampilan awal', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/9405'
    }
  }, async ({ page }) => {
    await page.goto('cdesa/form/1');

    await page.getByText('Warga Luar Desa').click();
    await page.getByRole('button', { name: ' Batal' }).click();

    await expect(page.getByText('Cari Nama Pemilik', { exact: true })).toBeVisible();
  });
});
