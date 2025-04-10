import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../../storage/auth/admin.json'),
});

test.describe('Tombol simpan tidak berfungsi setelah klik batal pada form ubah biodata penduduk #9409', () => {
  test('fix: perbaikan fungsi simpan penduduk', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/9409',
    },
  }, async ({ page }) => {
    // 1. Masuk ke halaman form ubah data penduduk (id = 98)
    await page.goto('penduduk/form/98');

    // 2. Klik tombol 'Batal'
    await page.getByRole('button', { name: ' Batal' }).click();

    // 3. Klik tombol 'Simpan' setelah batal
    await page.getByRole('button', { name: ' Simpan' }).click();

    // 4. Verifikasi halaman berhasil memuat teks 'Data Penduduk'
    await expect(page.getByText('Data Penduduk', { exact: true })).toBeVisible();
  });
});
