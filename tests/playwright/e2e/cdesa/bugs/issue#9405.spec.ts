import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../../storage/auth/admin.json'),
});

test.describe('Fungsi tombol batal pada ubah data C-Desa #9405', () => {
  test('fix: perbaiki simpan penduduk', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/9405'
    }
  }, async ({ page }) => {
    await page.goto('cdesa');
    await page.getByRole('link', { name: '' }).first().click();
    await page.getByText('Nama Penduduk NIK Pemilik').click();
    await page.getByRole('button', { name: ' Batal' }).click();
    await page.getByText('Nama Penduduk NIK Pemilik').click();
  });
});
