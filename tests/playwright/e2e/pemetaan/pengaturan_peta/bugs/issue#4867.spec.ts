import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../../../storage/auth/admin.json'),
});

test.describe('Bug/error: Hapus data yang ada sub #4867', () => {
  test('fix: hapus data yang ada sub', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/4867',
    },
  }, async ({ page }) => {
    await page.goto('line');

    // validasi data garis memiliki sub garis
    await page.getByTitle('Rincian Jalan', { exact: true }).click();
    await expect(page.locator('h5')).toContainText('Daftar Kategori Jalan');

    // jika ada sub garis, maka kembali untuk hapus parentnya
    await page.getByRole('link', { name: ' Kembali Ke Tipe Garis' }).click();

    // hapus garis yang ada sub garis
    await page.getByRole('link', { name: '' }).nth(1).click();
    await page.locator('#confirm-delete a').click();
    // maka akan muncul notifikasi tidak bisa dihapus
    await expect(page.locator('#notifikasi')).toContainText('Gagal menghapus data. Silakan hapus subdata terlebih dahulu.');
  });
});
