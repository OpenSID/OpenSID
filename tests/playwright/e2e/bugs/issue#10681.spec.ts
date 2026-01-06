import { test, expect } from '@playwright/test';

test.describe('Bug/error: data anjungan sukses di delet, tetapi keterangannya data gagal #10681', () => {
  test('fix: perbaikan data anjungan sukses di delet, tetapi keterangannya data gagal', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10681',
    },
  }, async ({ page }) => {
    // 1. Tambah data anjungan baru untuk memastikan ada data yang bisa dihapus
    await page.goto('anjungan/form');
    
    // Gunakan data random untuk memastikan test independen & bisa dijalankan berulang
    const ipAddress = `192.168.1.${Math.floor(Math.random() * 253) + 1}`;
    const macAddress = `00:1A:2B:3C:4D:${Math.floor(Math.random() * 99).toString().padStart(2, '0')}`;

    await page.locator('#ip_address').fill(ipAddress);
    await page.locator('#mac_address').fill(macAddress);
    await page.locator('#tambahDaftarAnjungan').click();

    // Tunggu redirect dan pastikan notifikasi sukses muncul
    await page.waitForURL('**/anjungan');
    await expect(page.locator('.alert.alert-success')).toBeVisible();

    // 2. Hapus data anjungan yang baru dibuat menggunakan bulk delete
    const row = page.locator('tr', { hasText: macAddress });
    await expect(row).toBeVisible(); // Pastikan baris ada sebelum dihapus

    // Klik checkbox pada baris tersebut
    await row.locator('input[name="id_cb[]"]').check();

    // Klik tombol hapus terpilih
    await page.locator('a.hapus-terpilih').click();

    // Klik tombol OK pada modal konfirmasi
    await page.locator('#ok-delete').click();

    // 3. Verifikasi hasil penghapusan
    // Tunggu notifikasi sukses penghapusan
    await expect(page.locator('.alert.alert-success')).toBeVisible();

    // Pastikan baris dengan mac address tersebut sudah tidak ada
    await expect(page.locator('tr', { hasText: macAddress })).toHaveCount(0);
  });
});
