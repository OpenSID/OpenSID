import { test, expect } from '@playwright/test';

test.describe('Bug/error: gambar Thema Bawaan tidak tampil atau menampilkan gambar rusak #11043', () => {
  test('fix: perbaiki tampilan gambar tema tidak tampil', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11043',
    },
  }, async ({ page }) => {

    // ✅ Path yang BOLEH diakses - theme thumbnails
    const allowedPaths = [
      'storage/app/themes/esensi/assets/thumbnail/preview-1.jpg',
      'storage/app/themes/lestari/assets/thumbnail/preview-1.jpg',
      'storage/app/themes/seruit-lite/assets/thumbnail/preview-1.jpg',
      'storage/app/themes/wira/assets/thumbnail/preview-1.jpg',
    ];

    for (const path of allowedPaths) {
      const response = await page.request.get(path);
      expect(response.status(), `Harus bisa diakses: ${path}`).toBe(200);
    }

    // ❌ Path yang TIDAK BOLEH diakses - file sensitif storage
    const blockedPaths = [
      'storage/framework/sessions/',
      'storage/framework/cache/',
      'storage/framework/views/',
      'storage/logs/',
      'storage/app/themes/esensi/assets/thumbnail/', // directory listing
      // Pastikan whitelist thumbnail hanya untuk file gambar, bukan ekstensi lain
      'storage/app/themes/esensi/assets/thumbnail/preview-1.jpg.php',
      'storage/app/themes/esensi/assets/thumbnail/secret.env',
    ];

    for (const path of blockedPaths) {
      const response = await page.request.get(path);
      expect(response.status(), `Harus diblokir: ${path}`).toBe(403);
    }

  });
});