import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Tujuan pindah kembali kosong ketika edit data di riwayat mutasi penduduk #10787', () => {
  test('fix: perbaikan tujuan pindah kembali kosong ketika edit data di riwayat mutasi penduduk', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10787',
    },
  }, async ({ page }) => {
    // 1. Langsung buka halaman log penduduk
    await page.goto('/penduduk_log');

    // 2. Cari baris log pertama yang berisi status 'Pindah' untuk memastikan
    //    data yang kita edit relevan.
    const barisLogPindah = page.locator('tr', { has: page.getByRole('cell', { name: 'Pindah' }) }).first();
    
    // Tunggu hingga baris tersebut muncul (data mungkin dimuat secara dinamis)
    await expect(barisLogPindah).toBeVisible({ timeout: 15000 });

    // 3. Klik tombol/link 'Ubah Data' pada baris tersebut
    await barisLogPindah.getByRole('link', { name: 'Ubah Data' }).click();

    // 4. Tunggu hingga modal untuk edit muncul
    const modalEdit = page.locator('#modalBox');
    await expect(modalEdit.getByRole('heading', { name: 'Ubah Data Catatan Peristiwa' })).toBeVisible();

    // 5. Lakukan verifikasi utama
    const dropdownTujuanPindah = modalEdit.locator('select[name="ref_pindah"]');
    
    // Pastikan dropdown terlihat di dalam modal
    await expect(dropdownTujuanPindah).toBeVisible();

    // ASERSI: Pastikan nilai yang terpilih pada dropdown BUKAN string kosong.
    await expect(dropdownTujuanPindah).not.toHaveValue('');

    console.log(`Verifikasi berhasil: Ditemukan nilai '${await dropdownTujuanPindah.inputValue()}' pada dropdown Tujuan Pindah.`);

  });
});
