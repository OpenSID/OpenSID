// Issue: #10884 — Tombol simpan dan keluar pada salin surat tidak berfungsi dengan benar
// https://github.com/OpenSID/OpenSID/issues/10884
import { test, expect } from '../../../utils/regressionTest';


test.describe('Bug/error: Tombol Simpan dan Keluar pada Salin Surat Tidak Berfungsi dengan benar #10884', () => {
  test('fix: klik Simpan dan Keluar setelah Salin Surat harus redirect ke halaman daftar surat', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10884',
    },
  }, async ({ page }) => {
    // 1. Pergi ke halaman daftar surat master
    await page.goto('surat_master');
    await expect(page).toHaveURL(/surat_master/);

    // 2. Cari baris di datatable yang punya tombol Salin (btn-default) tapi tidak punya Ubah (btn-warning)
    //    (menandakan surat bawaan sistem jenis=3)
    const barisSuratSistem = page.locator('table tbody tr').filter({
      has: page.locator('a.btn.btn-default'),
    }).filter({
      hasNot: page.locator('a.btn.btn-warning'),
    }).first();

    await expect(barisSuratSistem).toBeVisible();

    // 3. Klik tombol Salin (btn-default dengan ikon fa-copy) pada baris tersebut
    await barisSuratSistem.locator('a.btn.btn-default').click();
    await expect(page).toHaveURL(/surat_master\/form\/.*salin=1/);

    // 4. Isi Nama Layanan (dikosongkan otomatis saat salin)
    const inputNama = page.locator('input[name="nama"]');
    await expect(inputNama).toBeVisible();
    await inputNama.fill(`Surat Salin Test ${Date.now()}`);

    // 5. Klik tombol "Simpan dan Keluar"
    await page.locator('#simpan-keluar').first().click();

    // 6. Tunggu SweetAlert sukses muncul lalu klik OK
    await expect(page.locator('.swal2-popup')).toBeVisible({ timeout: 10_000 });
    await expect(page.locator('.swal2-title')).toContainText('Berhasil');
    await page.locator('.swal2-confirm').click();

    // 7. Setelah klik OK harus redirect kembali ke halaman daftar surat
    await expect(page).toHaveURL(/surat_master$/, { timeout: 5_000 });
  });
});
