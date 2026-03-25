import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Pesan Sukses Hapus Data Dokumen Penduduk #10958', () => {
  test('fix: perbaikan pesan sukses hapus data dokumen penduduk', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10958',
    },
  }, async ({ page }) => {
    
    // Navigasi ke halaman penduduk
    await page.goto('/penduduk');

    // Ambil ID penduduk pertama dari tabel dan navigasi ke halaman dokumennya
    const firstPendudukId = await page.locator('table tbody tr:first-child a[href*="/penduduk/dokumen/"]')
      .getAttribute('href')
      .then(href => href?.split('/penduduk/dokumen/')[1]);

    await page.goto(`/penduduk/dokumen/${firstPendudukId}`);

    // Klik tombol hapus (tombol pertama dengan data-target="#confirm-delete")
    await page.locator('a.btn.bg-maroon[data-target="#confirm-delete"]').first().click();

    // Tunggu modal konfirmasi muncul
    await expect(page.locator('.modal-content')).toBeVisible();
    await expect(page.locator('.modal-title')).toContainText('Konfirmasi Penghapusan Data');

    // Ketik "HAPUS" pada input konfirmasi
    await page.locator('#confirm-input').fill('HAPUS');

    // Tunggu tombol hapus aktif (tidak disabled lagi)
    await expect(page.locator('#ok-delete')).not.toBeDisabled();

    // Klik tombol hapus
    await page.locator('a.btn-ok').click();

    // Verifikasi pesan sukses muncul
    await expect(page.locator('body')).toContainText('Dokumen/data berhasil dihapus');

  });
});
