import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../../../storage/auth/admin.json'),
});

test.describe('Bug/error: Hapus data yang ada sub tipe lokasi #9454', () => {
  test('fix: hapus data yang ada sub tipe lokasi', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/9454',
    },
  }, async ({ page }) => {
    await page.goto('point');

    // validasi data garis memiliki sub garis
    await page.getByTitle('Rincian Sarana Kesehatan', { exact: true }).click();
    await expect(page.locator('h5')).toContainText('Kategori Sarana Kesehatan');

    // jika ada sub garis, maka kembali untuk hapus parentnya
    await page.getByRole('link', { name: ' Kembali Ke Tipe Lokasi' }).click();

    // hapus garis yang ada sub garis
    await page.locator('a[data-target="#confirm-delete"]').last().click();
    await page.locator('#confirm-delete a').click();
    // maka akan muncul notifikasi tidak bisa dihapus
    await expect(page.locator('#notifikasi')).toContainText('Gagal menghapus data. Silakan hapus subdata terlebih dahulu.');
  });

  test('fix: hapus masal data yang ada sub tipe lokasi', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/9454',
    },
  }, async ({ page }) => {
    await page.goto('point');

    await expect(page.getByRole('gridcell', { name: 'Sarana Kesehatan' })).toBeVisible();
    await page.locator('#checkall').check();
    await page.getByRole('link', { name: ' Hapus' }).click();
    await page.locator('#confirm-delete a').click();
    await expect(page.locator('#notifikasi')).toContainText('Gagal menghapus data. Silakan hapus subdata terlebih dahulu.');
  });
});