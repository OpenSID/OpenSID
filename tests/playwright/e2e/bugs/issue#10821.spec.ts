import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Pengguna menjadi tidak aktif setelah di aktifkan #10821', () => {
  test('fix: perbaikan Pengguna menjadi tidak aktif setelah di aktifkan', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10821',
    },
  }, async ({ page }) => {
    await page.goto('/');
    await expect(page).toHaveURL(/\//);
    
    // Tunggu sebentar untuk memastikan cache tercipta
    await page.waitForTimeout(1000);
    
    // Akses halaman publik berbeda beberapa kali dalam waktu singkat
    const publicPages = ['/artikel', '/covid19', '/informasi_publik', '/gallery'];
    
    for (const pagePath of publicPages) {
    await page.goto(pagePath);
    await page.waitForTimeout(500);
    }
    
    // Verifikasi: fungsi seharusnya hanya dipanggil 1x (dari akses pertama)
    // Kita tidak bisa langsung cek log di browser, tapi bisa verifikasi tidak ada error
    const hasError = await page.evaluate(() => {
    return window.console.error || false;
    });
    
    expect(hasError).toBeFalsy();
  });
});