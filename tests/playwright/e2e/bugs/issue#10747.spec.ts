import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug: Gagal Reset pin di Layanan Mandiri #10747', () => {
  test('fix: perbaiki Gagal Reset pin di Layanan Mandiri', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10747',
    },
  }, async ({ page }) => {
    // Navigasi ke halaman Layanan Mandiri
    await page.goto('mandiri');

    // Tunggu halaman fully loaded
    await page.waitForLoadState('networkidle');

    // Klik tombol "Tambah" untuk reset PIN baru
    await page.getByRole('button', { name: 'Tambah' }).click();

    // Tunggu modal muncul
    await page.waitForSelector('#modalBox', { state: 'visible' });

    // Pilih penduduk pertama dari dropdown
    const selectPenduduk = page.locator('#id_pend');
    await selectPenduduk.selectOption({ index: 1 });

    // Pilih opsi kirim via WhatsApp
    await page.getByLabel('Kirim PIN via WhatsApp').check();

    // Klik tombol Simpan
    await page.getByRole('button', { name: 'Simpan' }).click();

    // Tunggu redirect dan modal PIN muncul
    await page.waitForSelector('#pinBox', { state: 'visible', timeout: 10000 });

    // Verifikasi modal PIN terbuka dengan PIN yang ditampilkan
    await expect(page.locator('#pinBox')).toBeVisible();
    await expect(page.getByText('Kode PIN :')).toBeVisible();

    // Klik tombol "Kirim" untuk mengirim PIN via WhatsApp
    await page.getByRole('button', { name: 'Kirim' }).click();

    // Verifikasi redirect ke WhatsApp API (tidak ada error)
    await page.waitForURL(/api\.whatsapp\.com/, { timeout: 10000 });

    // Verifikasi URL WhatsApp mengandung parameter yang benar
    const currentUrl = page.url();
    expect(currentUrl).toContain('api.whatsapp.com');
    expect(currentUrl).toContain('phone=');
    expect(currentUrl).toContain('text=');
  });

  test('fix: reset PIN existing penduduk dan kirim via WhatsApp', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10747',
    },
  }, async ({ page }) => {
    // Navigasi ke halaman Layanan Mandiri
    await page.goto('mandiri');

    // Tunggu halaman fully loaded
    await page.waitForLoadState('networkidle');

    // Klik tombol aksi "Reset PIN" pada baris pertama tabel
    await page.locator('table tbody tr:first-child .btn-group .dropdown-toggle').click();
    await page.getByRole('link', { name: 'Reset PIN' }).click();

    // Tunggu modal muncul
    await page.waitForSelector('#modalBox', { state: 'visible' });

    // Pilih opsi kirim via WhatsApp
    await page.getByLabel('Kirim PIN via WhatsApp').check();

    // Klik tombol Simpan
    await page.getByRole('button', { name: 'Simpan' }).click();

    // Tunggu redirect dan modal PIN muncul
    await page.waitForSelector('#pinBox', { state: 'visible', timeout: 10000 });

    // Verifikasi modal PIN terbuka
    await expect(page.locator('#pinBox')).toBeVisible();
    await expect(page.getByText('Kode PIN :')).toBeVisible();

    // Verifikasi PIN tersimpan di hidden input
    const pinInput = page.locator('#pin');
    await expect(pinInput).toHaveValue(/^\d{6}$/); // PIN harus 6 digit angka

    // Klik tombol "Kirim"
    await page.getByRole('button', { name: 'Kirim' }).click();

    // Verifikasi redirect ke WhatsApp API tanpa error
    await page.waitForURL(/api\.whatsapp\.com/, { timeout: 10000 });

    // Verifikasi URL WhatsApp valid
    const currentUrl = page.url();
    expect(currentUrl).toContain('api.whatsapp.com');
    expect(currentUrl).toContain('phone=%2B62'); // Format nomor Indonesia
    expect(currentUrl).toContain('text=');
  });
});
