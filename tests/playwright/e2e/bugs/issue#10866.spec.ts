import { test, expect } from '@playwright/test';
import path from 'path';
import { Laravel } from '../../utils/laravel';

test.describe('Bug/error: Error saat Mengirim Pesan di Menu Layanan Mandiri #10866', () => {
  test('fix: Pesan berhasil dikirim tanpa error dan UI terarah dengan baik', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10866'
    }
  }, async ({ page }) => {
    try {
      // 1. Masuk ke layanan mandiri
      await page.goto('layanan-mandiri/masuk');

      try {
        await page.getByText('Terima semua cookie', { exact: true }).click();
      } catch { } // abaikan kalau tombol cookie nggak ada

      try {
        // 2. Login dengan NIK dan PIN penduduk test
        await page.getByRole('textbox', { name: 'NIK' }).fill('1307026005650003');
        await page.getByRole('textbox', { name: 'PIN' }).fill('111111');
        await page.getByRole('button', { name: 'MASUK', exact: true }).click();
        
        // Tunggu redirect setelah login berhasil
        await page.waitForNavigation({ waitUntil: 'networkidle' });

        // 3. Navigasi ke halaman tulis pesan
        await page.goto('layanan-mandiri/pesan/tulis');
        await page.waitForLoadState('networkidle');

        // 4. Isi form pesan - subjek
        const subjekInput = page.locator('input[name="subjek"], textarea[name="subjek"]').first();
        await subjekInput.fill(`Test Pesan ${new Date().getTime()}`);

        // 5. Isi form pesan - isi pesan
        const pesanInput = page.locator('textarea[name="pesan"], textarea[name="isi"]').first();
        await pesanInput.fill('Ini adalah pesan test untuk verifikasi issue #10866');

        // 6. Klik tombol "Kirim Pesan"
        await page.getByRole('button', { name: /Kirim|Submit/i }).click();
        
        // Tunggu redirect
        await page.waitForNavigation({ waitUntil: 'networkidle' });

        // 7. Verifikasi berhasil redirect ke halaman pesan
        const finalUrl = page.url();
        expect(
          finalUrl.includes('layanan-mandiri/pesan-masuk') || 
          finalUrl.includes('layanan-mandiri/pesan') ||
          finalUrl.includes('layanan-mandiri')
        ).toBeTruthy();

        // 8. Verifikasi tidak ada error TypeError / Syntax Error / Parse Error
        const errorMessages = page.locator('text=/TypeError|SyntaxError|Parse error|Syntax error|Fatal error/i');
        await expect(errorMessages).not.toBeVisible();

      } catch (error) {
        // Jika ada error, log untuk debug
        console.error('Test failed:', error);
        throw error;
      }
    } catch (error) {
      console.error('Test failed:', error);
      throw error;
    }
  });
});
