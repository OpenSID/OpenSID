// Issue: #4867 — Hapus data yang ada sub pada tipe garis
// https://github.com/OpenSID/OpenSID/issues/4867
import { test, expect } from '../../../utils/regressionTest';


test.describe('Bug/error: Hapus data yang ada sub #4867', () => {
  test('fix: hapus data yang ada sub', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/4867',
    },
  }, async ({ page }) => {
    await page.goto('line');

    // validasi data garis memiliki sub garis
    // Rekonsiliasi rilis-dev (#4867): judul kategori garis yang memiliki sub adalah
    // 'Rincian Jalan setapak', bukan 'Rincian Jalan'. TRIASE: verifikasi terhadap data
    // kategori garis pada desa.zip saat menjalankan folder peta/ dengan DB ter-reset.
    await page.getByTitle('Rincian Jalan setapak', { exact: true }).click();
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
