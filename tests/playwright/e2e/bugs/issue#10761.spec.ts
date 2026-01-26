import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Notifikasi / Pesan Gagal Double di Layanan Mandiri #10761', () => {
  test('fix: perbaikan Notifikasi / Pesan Gagal Double di Layanan Mandiri', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10761',
    },
  }, async ({ page }) => {
    await page.goto('/layanan-mandiri/masuk');

    // Masukkan NIK dan PIN yang salah
    await page.locator('input[name="nik"]').fill('0000000000000000');
    await page.locator('input[name="password"]').fill('salah');

    // Klik tombol masuk
    await page.locator('button[type="submit"]').click();

    // Halaman akan memuat ulang, jadi kita tunggu URL nya.
    await page.waitForURL('**/layanan-mandiri/masuk');

    // Periksa jumlah alert-danger
    const dangerAlerts = page.locator('.alert-danger');
    await expect(dangerAlerts).toHaveCount(1);
    
  });
});
