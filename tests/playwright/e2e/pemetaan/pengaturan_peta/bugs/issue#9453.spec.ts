import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../../../storage/auth/admin.json'),
});

test.describe('Bug/error: Hapus data yang ada sub tipe area #9453', () => {
  test('fix: hapus data yang ada sub tipe area', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/9453',
    },
  }, async ({ page }) => {
    await page.goto('polygon');

    // validasi data garis memiliki sub garis
    await page.getByTitle('Rincian rawan topan', { exact: true }).click();
    await expect(page.locator('h5')).toContainText('Daftar Kategori rawan topan');

    // jika ada sub garis, maka kembali untuk hapus parentnya
    await page.getByRole('link', { name: ' Kembali Ke Tipe Area' }).click();

    // hapus garis yang ada sub garis
    await page.locator('a[data-target="#confirm-delete"]').last().click();
    await page.locator('#confirm-delete a').click();
    // maka akan muncul notifikasi tidak bisa dihapus
    await expect(page.locator('#notifikasi')).toContainText('Gagal menghapus data. Silakan hapus subdata terlebih dahulu.');
  });

  test('fix: hapus masal data yang ada sub tipe area', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/9453',
    },
  }, async ({ page }) => {
    await page.goto('polygon');

    await expect(page.getByRole('gridcell', { name: 'rawan topan' })).toBeVisible();
    await page.locator('#checkall').check();
    await page.getByRole('link', { name: ' Hapus' }).click();
    await page.locator('#confirm-delete a').click();
    await expect(page.locator('#notifikasi')).toContainText('Gagal menghapus data. Silakan hapus subdata terlebih dahulu.');
  });
});
