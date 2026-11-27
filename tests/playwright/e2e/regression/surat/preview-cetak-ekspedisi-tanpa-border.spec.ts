// Issue: #10712 — Hasil preview cetak buku ekspedisi tidak ada border line
// https://github.com/OpenSID/OpenSID/issues/10712
import { test, expect } from '../../../utils/regressionTest';


test.describe('Bug/error: Hasil Previews Cetak Buku Ekspedisi tidak ada border line pada table nya #10712', () => {
  test('fix: perbaikan Hasil Previews Cetak Buku Ekspedisi tidak ada border line pada table nya', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10712',
    },
  }, async ({ page }) => {

    // 1. Akses halaman Buku Ekspedisi
    await page.goto('ekspedisi');

    // 2. Pastikan halaman sudah loading dengan benar
    await expect(page.locator('text=Buku Ekspedisi').first()).toBeVisible();

    // 3. Klik tombol Cetak/Unduh
    await page.getByText('Cetak/Unduh', { exact: true }).click();
    await page.getByRole('link', { name: 'Cetak' }).click();

    // 4. Tunggu dialog muncul beserta isinya (konten dimuat via AJAX)
    await expect(page.locator('#modalBox')).toBeVisible();
    // Konten modal dimuat via AJAX — tunggu tombol submit muncul
    await expect(page.locator('#btn-ok')).toBeVisible({ timeout: 10000 });

    // 5. Tunggu popup cetak muncul
    // Dialog menggunakan form id="validasi" dengan target="_blank"
    // Tombol submit memiliki id="btn-ok"
    const page2Promise = page.waitForEvent('popup');
    await page.locator('#btn-ok').click();
    const page2 = await page2Promise;

    // 6. Tunggu halaman cetak selesai dimuat
    await page2.waitForLoadState('domcontentloaded');

    // 7. Verifikasi judul dokumen "BUKU EKSPEDISI"
    await expect(page2.locator('text=BUKU EKSPEDISI').first()).toBeVisible({ timeout: 10000 });

    // 8. Verifikasi bahwa table header ditampilkan lengkap
    await expect(page2.locator('text=NOMOR URUT')).toBeVisible();
    await expect(page2.locator('text=TANGGAL PENGIRIMAN')).toBeVisible();
    await expect(page2.locator('text=TANGGAL DAN NOMOR SURAT')).toBeVisible();

    // 9. Verifikasi bahwa kolom KETERANGAN ada (tidak terpotong)
    await expect(page2.locator('text=KETERANGAN').first()).toBeVisible({ timeout: 10000 });

    // 10. Verifikasi table memiliki class border (ini adalah bug yang diperbaiki)
    // Sebelum fix, tabel tidak memiliki border; setelah fix, tabel memakai class "border thick"
    await expect(page2.locator('table.border.thick')).toBeVisible();

    // 11. Tutup popup cetak
    await page2.close();

    // 12. Kembali ke halaman utama dan verifikasi masih bisa cetak ulang
    await expect(page.locator('text=Buku Ekspedisi').first()).toBeVisible();
  });
});
