import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

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

    // 2. Cari baris di datatable yang punya tombol Salin tapi tidak punya tombol Ubah
    //    (menandakan surat bawaan sistem)
    const barisSuratSistem = page.locator('table tbody tr').filter({
      has: page.locator('a.btn.bg-olive[title="Salin"]'),
    }).filter({
      hasNot: page.locator('a.btn.bg-orange'),
    }).first();

    await expect(barisSuratSistem).toBeVisible();

    // 3. Klik tombol Salin pada baris tersebut
    await barisSuratSistem.locator('a.btn.bg-olive[title="Salin"]').click();
    await expect(page).toHaveURL(/surat_master\/salin\//);

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
