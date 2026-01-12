import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Fungsi Ambil Foto dari Kamera di Menu Data Anggota Kelompok tidak berfungsi #10716', () => {
  test('fitur kamera harus berfungsi di halaman ubah biodata penduduk', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10716',
    },
  }, async ({ page, context }) => {
    // Grant camera permission ke browser context
    await context.grantPermissions(['camera']);

    // 1. Pergi ke halaman /penduduk
    await page.goto('penduduk');
    await page.waitForLoadState('networkidle');
    
    // Tunggu tabel penduduk muncul
    await page.waitForSelector('table', { timeout: 10000 });

    // 2. Pilih salah 1 data penduduk
    // Klik checkbox data pertama yang tersedia
    const firstCheckbox = page.locator('input[type="checkbox"][name="id_cb[]"]').first();
    if (await firstCheckbox.count() > 0) {
      await firstCheckbox.check();
      
      // 3. Klik tombol Pilih Aksi
      const pilihAksiButton = page.locator('button:has-text("Pilih Aksi")');
      await pilihAksiButton.click();
      
      // 4. Klik menu "Ubah Biodata Penduduk" dari dropdown
      await page.locator('a:has-text("Ubah Biodata Penduduk"), .dropdown-menu a:has-text("Ubah Biodata")').click();
      
      // 4. Ketika halaman biodata penduduk tampil
      await page.waitForLoadState('networkidle');
      
      // Tunggu form biodata muncul
      await page.waitForSelector('form', { timeout: 10000 });

      // Periksa Permissions-Policy header di halaman admin
      const currentUrl = page.url();
      const response = await page.goto(currentUrl);
      const headers = response?.headers() || {};
      const permissionsPolicy = headers['permissions-policy'] || '';

      // 5. Pastikan Permissions-Policy TIDAK memblokir camera di halaman admin
      const isCameraBlocked = permissionsPolicy.includes('camera=()');
      expect(isCameraBlocked).toBe(false);

      // Atau pastikan tidak ada Permissions-Policy sama sekali di backend
      // karena SecurityHeaders hanya untuk frontend (Web_Controller)
      expect(permissionsPolicy).toBe('');

      // 6. Test akses MediaDevices API
      const mediaDevicesAvailable = await page.evaluate(() => {
        return typeof navigator.mediaDevices !== 'undefined' && 
               typeof navigator.mediaDevices.getUserMedia === 'function';
      });
      expect(mediaDevicesAvailable).toBe(true);

      // 7. Klik tombol Kamera
      const cameraButton = page.locator('button:has-text("Kamera"), .btn:has-text("Kamera")');
      await expect(cameraButton).toBeVisible();
      
      // Pastikan tombol tidak disabled
      await expect(cameraButton).toBeEnabled();
      
      // 8. Klik tombol kamera
      await cameraButton.click();
      
      // 9. Pastikan TIDAK ADA pesan error yang muncul
      // "Anda tidak memberikan izin untuk menggunakan kamera, mohon periksa kembali dan pastikan website Anda menggunakan ssl/https."
      const errorMessage = page.locator('text=/tidak memberikan izin.*kamera/i, text=/mohon periksa kembali.*ssl/i');
      await expect(errorMessage).not.toBeVisible({ timeout: 3000 });
      
      // Pastikan tidak ada alert/dialog error
      page.on('dialog', async dialog => {
        const message = dialog.message();
        expect(message).not.toContain('tidak memberikan izin');
        expect(message).not.toContain('ssl/https');
        await dialog.accept();
      });

      // 10. Test akses getUserMedia (kamera) di browser harus berhasil
      const cameraAccess = await page.evaluate(async () => {
        try {
          const stream = await navigator.mediaDevices.getUserMedia({ video: true });
          // Stop stream setelah test
          stream.getTracks().forEach(track => track.stop());
          return { success: true, error: null };
        } catch (error: any) {
          return { success: false, error: error.name + ': ' + error.message };
        }
      });

      // Pastikan akses kamera berhasil tanpa error
      expect(cameraAccess.success).toBe(true);
    }
  });
});
