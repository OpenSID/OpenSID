import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('[BUG] Pesan Konfirmasi Kembalikan Foto Tidak Sesuai (Menampilkan Informasi Penghapusan Data) #11157', () => {
  test('fix: perbaiki aksi kembalikan foto penduduk', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11157',
    },
  }, async ({ page }) => {
    await page.goto('penduduk');

    await page.getByText('Pilih Aksi').nth(1).click();
    await page.getByRole('link', { name: ' Ubah Biodata Penduduk' }).click();
    await page.goto('http://opensid-premium.test/index.php/penduduk/form/5144');
    await page.getByRole('link', { name: ' Kembalikan' }).click();
    await expect(page.locator('#confirm-status')).toContainText('Apakah Anda yakin ingin mengembalikan foto ke foto bawaan?');
  });
});