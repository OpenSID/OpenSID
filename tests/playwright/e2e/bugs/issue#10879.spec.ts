import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Tampilan Foto Rusak di Edit Lokasi jika Lokasi Tidak ada foto #10879', () => {
  test('fix: Preview gambar tampil dengan benar di halaman lokasi', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10879',
    },
  }, async ({ page }) => {
    // 1. Navigasi ke halaman Lokasi
    await page.goto('plan');
    await page.waitForLoadState('networkidle');

    // Tunggu tabel data lokasi muncul
    await page.waitForSelector('table', { timeout: 10000 });

    // 2. Verifikasi bahwa ada data lokasi di tabel
    const lokasisTable = page.locator('table tbody tr');
    const locIndexCount = await lokasisTable.count();
    
    if (locIndexCount === 0) {
      test.skip(true, 'Tidak ada data lokasi untuk ditest');
      return;
    }

    // 3. Pilih salah satu data lokasi pada tabel dan klik tombol 'Ubah Data'
    const firstRow = lokasisTable.first();
    const editButton = firstRow.locator('a, button').filter({ hasText: /Ubah|Edit/ }).first();
    
    if (await editButton.count() === 0) {
      test.skip(true, 'Tidak ada tombol Ubah Data di tabel');
      return;
    }

    await editButton.click();
    await page.waitForLoadState('networkidle');

    // 4. Periksa preview gambar di halaman edit lokasi
    const previewImage = page.locator('img[alt*="Foto"], img[alt*="foto"], img[src*="upload"], .img-preview img');
    
    if (await previewImage.count() > 0) {
      // Verifikasi gambar tampil dengan benar (tidak broken)
      const isImageLoaded = await previewImage.first().evaluate((img: HTMLImageElement) => {
        return img.complete && img.naturalWidth > 0 && img.naturalHeight > 0;
      });
      
      expect(isImageLoaded, 'Gambar preview harus tampil dengan benar (tidak broken)').toBe(true);
    }
  });
});
