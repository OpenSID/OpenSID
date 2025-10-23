import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../../storage/auth/admin.json'),
});

test.describe('Fungsi tombol batal pada ubah data C-Desa #9405', () => {
  test('fix: perbaikan tombol batal kembali ke tampilan awal', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/9405',
    },
  }, async ({ page }) => {
    // 1. Masuk ke halaman form ubah data C-Desa dengan ID = 1
    await page.goto('cdesa/form/1');

    // 2. Klik teks 'Warga Luar Desa' (simulasi interaksi atau perubahan data)
    await page.getByText('Warga Luar Desa').click();

    // 3. Klik tombol 'Batal'
    await page.getByRole('button', { name: ' Batal' }).click();

    // 4. Verifikasi kembali ke tampilan awal dengan teks 'Cari Nama Pemilik'
    await expect(page.getByText('Cari Nama Pemilik', { exact: true })).toBeVisible();
  });
});
