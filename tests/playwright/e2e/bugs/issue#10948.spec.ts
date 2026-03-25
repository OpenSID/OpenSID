import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('nik tidak tampil dengan benar #10948', () => {
  test('fix: perbaikan nik tidak tampil dengan benar saat export', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10948',
    },
  }, async ({ page }) => {
    // 1. Navigasi ke halaman daftar program bantuan untuk mendapatkan ID program secara dinamis
    await page.goto('/program_bantuan');
    await page.waitForLoadState('networkidle');
    await expect(page.locator('h1')).toContainText('Program Bantuan');

    // 2. Cari link untuk mencetak daftar peserta dari program pertama di tabel
    const cetakPesertaLink = page.locator('a[href*="/peserta_bantuan/daftar/"]').first();
    await expect(cetakPesertaLink).toBeVisible();

    // 3. Klik link untuk navigasi ke halaman cetak
    await cetakPesertaLink.click();
    await page.waitForLoadState('networkidle');

    // 2. Verifikasi judul halaman sesuai dengan yang diatur di controller.
    await expect(page).toHaveTitle('Peserta Bantuan');

    // 3. Verifikasi bahwa tabel untuk tanda tangan (ttd) ada dan terlihat.
    // Ini mengindikasikan bahwa CSS dan struktur untuk tanda tangan sudah dimuat.
    const signatureTable = page.locator('table#ttd');
    await expect(signatureTable).toBeVisible();

    // 4. Verifikasi bahwa nama jabatan penanda tangan (Kepala Desa dan Sekdes) ada di dalam tabel ttd.
    await expect(signatureTable).toContainText('Kepala Desa');
    await expect(signatureTable).toContainText('Sekretaris Desa');

    // 5. Verifikasi bahwa tabel utama yang berisi daftar peserta juga ada.
    // Kita asumsikan tabel ini memiliki class 'table' dan 'table-bordered'.
    const mainTable = page.locator('table.table.table-bordered');
    await expect(mainTable).toBeVisible();

    // 6. Verifikasi header pada tabel utama untuk memastikan data peserta dimuat.
    // Mengecek kolom umum seperti NIK dan Nama.
    const tableHeader = mainTable.locator('thead');
    await expect(tableHeader).toContainText('NIK');
    await expect(tableHeader).toContainText('Nama Lengkap');

  });
});
