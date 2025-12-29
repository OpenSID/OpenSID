import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Format Tanggal Inventaris antara Halaman dan Hasil Cetak tidak Konsisten #10662', () => {
  test('fix: perbaikan Format Tanggal Inventaris antara Halaman dan Hasil Cetak tidak Konsisten', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10662',
    },
  }, async ({ page }) => {
    await page.goto('inventaris_gedung');

    // Tunggu hingga tabel terlihat, antisipasi pemuatan asinkron
    await page.waitForSelector('table.table tbody tr');

    // Ambil tanggal dari baris data pertama pada tabel
    const tanggalDiHalaman = await page
      .locator('table.table tbody tr:first-child td:nth-child(5)')
      .textContent();

    // Mulai menunggu halaman baru (untuk pencetakan) sebelum mengklik tombol
    const pagePromise = page.context().waitForEvent('page');

    // Klik tombol cetak.
    await page.locator('a.buttons-print').click();

    // Tunggu halaman baru terbuka
    const newPage = await pagePromise;
    await newPage.waitForLoadState();

    // Tunggu tabel di halaman cetak
    await newPage.waitForSelector('table.table tbody tr');
    
    // Ambil tanggal dari baris data pertama pada tabel di halaman cetak
    const tanggalDiCetak = await newPage
      .locator('table.table tbody tr:first-child td:nth-child(5)')
      .textContent();

    // Pastikan format tanggal konsisten setelah perbaikan
    expect(tanggalDiCetak?.trim()).toBe(tanggalDiHalaman?.trim());

    // Tutup halaman cetak
    await newPage.close();
  });
});
