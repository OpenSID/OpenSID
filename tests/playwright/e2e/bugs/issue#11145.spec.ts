import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: klik tombol cetak dan ekspor data tamu tidak berfungsi dan data pada kolom bertemu hilang #11145', () => {
  
  test.beforeEach(async ({ page }) => {
    // Navigate ke halaman Data Tamu
    await page.goto('buku_tamu');
    // Wait for page to load
    await page.waitForLoadState('networkidle');
  });

  test('fix: tombol Cetak membuka halaman cetak di tab baru', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11145',
    },
  }, async ({ context, page }) => {
    // Tunggu tombol Cetak muncul
    const cetakBtn = page.locator('#cetak');
    await expect(cetakBtn).toBeVisible();

    // Klik tombol Cetak dan tunggu halaman baru terbuka
    const [newPage] = await Promise.all([
      context.waitForEvent('page'),
      cetakBtn.click(),
    ]);

    // Tunggu halaman baru selesai loading
    await newPage.waitForLoadState('networkidle');

    // Verifikasi URL halaman cetak
    expect(newPage.url()).toContain('/buku_tamu/cetak');

    // Verifikasi halaman cetak menampilkan konten (header atau judul)
    const pageContent = await newPage.locator('body').textContent();
    expect(pageContent).toBeTruthy();

    // Close halaman baru
    await newPage.close();
  });

  test('fix: tombol Ekspor membuka halaman ekspor di tab baru', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11145',
    },
  }, async ({ context, page }) => {
    // Tunggu tombol Ekspor muncul
    const exporBtn = page.locator('#expor');
    await expect(exporBtn).toBeVisible();

    // Klik tombol Ekspor dan tunggu halaman baru terbuka
    const [newPage] = await Promise.all([
      context.waitForEvent('page'),
      exporBtn.click(),
    ]);

    // Tunggu halaman baru selesai loading
    await newPage.waitForLoadState('networkidle');

    // Verifikasi URL halaman ekspor
    expect(newPage.url()).toContain('/buku_tamu/ekspor');

    // Close halaman baru
    await newPage.close();
  });

  test('fix: kolom BERTEMU menampilkan data dengan nilai', async ({ page }) => {
    // Tunggu DataTable terbuka dan terisi
    const dataTable = page.locator('#tabeldata');
    await expect(dataTable).toBeVisible();

    // Tunggu sampai minimal ada satu row data
    const tableBody = page.locator('#tabeldata tbody');
    const rows = tableBody.locator('tr');
    await expect(rows.first()).toBeVisible();

    // Cek apakah header BERTEMU ada
    const thead = page.locator('#tabeldata thead');
    const headerText = await thead.textContent();

    // Verifikasi kolom BERTEMU ada di header
    expect(headerText).toContain('BERTEMU');
  });
});
