import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Alert Error di Pengaduan Kehadiran apabila Si Pelapor di hapus di Pendaftaran Layanan Mandiri #10703', () => {
  test('fix: perbaiki Alert Error di Pengaduan Kehadiran apabila Si Pelapor di hapus di Pendaftaran Layanan Mandiri', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10703',
    },
  }, async ({ page }) => {
    await page.goto('mandiri');

    // Gunakan filter untuk mencari pengguna yang spesifik
    await page.getByRole('button', { name: 'Filter' }).click();
    await page.locator('input[name="filter[nik]"]').fill('3505123112840001');
    await page.getByRole('button', { name: 'Cari' }).click();

    // Tunggu hingga tabel selesai dimuat
    await page.waitForSelector('.odd');

    // Klik tombol hapus
    await page.locator('.odd').getByRole('button', { name: 'Hapus' }).click();

    // Terima dialog konfirmasi penghapusan
    await page.getByRole('button', { name: 'Ya, Hapus!' }).click();

    // Verifikasi pesan error
    await expect(page.getByText('Data pendaftar layanan mandiri tidak dapat dihapus karena datanya sudah digunakan di modul Kehadiran Pengaduan.')).toBeVisible();

    // Pastikan data tidak terhapus
    await expect(page.getByRole('cell', { name: 'BAGONG' })).toBeVisible();
  });
});
