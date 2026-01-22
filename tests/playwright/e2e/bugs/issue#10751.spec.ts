import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Perbaiki Pemanggilan Logo default jika Kelompok tidak upload logonya #10751', () => {
  test('fix: Logo kelompok harus tampil (default logo desa jika tidak ada logo kelompok)', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10751',
    },
  }, async ({ page }) => {
    // 1. Halaman Admin - Kependudukan - Kelompok
    await page.goto('kelompok');
    await page.waitForLoadState('networkidle');

    // Tunggu tabel kelompok muncul
    await page.waitForSelector('table', { timeout: 10000 });

    // 2. Cari kelompok pertama yang tersedia dan klik Detail Anggota
    const detailButton = page.locator('a[title="Lihat Detail Data"], a:has-text("Detail Anggota")').first();
    
    // Jika tidak ada data kelompok, skip test
    if (await detailButton.count() === 0) {
      test.skip(true, 'Tidak ada data kelompok untuk ditest');
      return;
    }

    await detailButton.click();
    await page.waitForLoadState('networkidle');

    // 3. Verifikasi halaman detail anggota kelompok tampil
    await page.waitForSelector('.box-body', { timeout: 10000 });

    // 4. Cari gambar logo kelompok di bagian atas samping kiri (dalam tabel rincian)
    const logoImage = page.locator('img.img-thumbnail[alt*="Logo"]');
    await expect(logoImage).toBeVisible();

    // 5. Verifikasi gambar tidak broken (naturalWidth > 0 berarti gambar berhasil dimuat)
    const isImageLoaded = await logoImage.evaluate((img: HTMLImageElement) => {
      return img.complete && img.naturalWidth > 0 && img.naturalHeight > 0;
    });
    expect(isImageLoaded, 'Gambar logo harus berhasil dimuat (tidak broken)').toBe(true);

    // 6. Verifikasi src gambar tidak kosong dan tidak mengarah ke path kosong
    const imageSrc = await logoImage.getAttribute('src');
    expect(imageSrc, 'Atribut src gambar tidak boleh kosong').toBeTruthy();
    expect(imageSrc, 'Path gambar tidak boleh berakhir dengan /desa/logo/ tanpa nama file').not.toMatch(/\/desa\/logo\/?$/);
  });

  test('fix: Semua kelompok harus memiliki logo yang tampil (tidak ada gambar broken)', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10751',
    },
  }, async ({ page }) => {
    // 1. Pergi ke halaman kelompok
    await page.goto('kelompok');
    await page.waitForLoadState('networkidle');
    await page.waitForSelector('table', { timeout: 10000 });

    // 2. Ambil semua link detail anggota
    const detailLinks = page.locator('a[title="Lihat Detail Data"]');
    const linkCount = await detailLinks.count();

    if (linkCount === 0) {
      test.skip(true, 'Tidak ada data kelompok untuk ditest');
      return;
    }

    // 3. Test maksimal 3 kelompok untuk efisiensi
    const maxTest = Math.min(linkCount, 3);

    for (let i = 0; i < maxTest; i++) {
      // Kembali ke halaman kelompok
      await page.goto('kelompok');
      await page.waitForLoadState('networkidle');
      await page.waitForSelector('table', { timeout: 10000 });

      // Klik detail kelompok ke-i
      const detailButton = page.locator('a[title="Lihat Detail Data"]').nth(i);
      const kelompokName = await detailButton.locator('xpath=ancestor::tr').locator('td').nth(2).textContent();
      
      await detailButton.click();
      await page.waitForLoadState('networkidle');
      await page.waitForSelector('.box-body', { timeout: 10000 });

      // Cari gambar logo
      const logoImage = page.locator('img.img-thumbnail[alt*="Logo"]');
      await expect(logoImage, `Logo untuk kelompok "${kelompokName}" harus terlihat`).toBeVisible();

      // Verifikasi gambar tidak broken
      const isImageLoaded = await logoImage.evaluate((img: HTMLImageElement) => {
        return img.complete && img.naturalWidth > 0 && img.naturalHeight > 0;
      });
      expect(isImageLoaded, `Gambar logo untuk kelompok "${kelompokName}" harus berhasil dimuat (tidak broken)`).toBe(true);

      // Verifikasi src tidak kosong atau invalid
      const imageSrc = await logoImage.getAttribute('src');
      expect(imageSrc, `Src gambar untuk kelompok "${kelompokName}" tidak boleh kosong`).toBeTruthy();
      expect(imageSrc, `Path gambar untuk kelompok "${kelompokName}" tidak boleh invalid`).not.toMatch(/\/desa\/logo\/?$/);
    }
  });

  test('fix: Logo default (logo desa) harus tampil untuk kelompok tanpa logo', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10751',
    },
  }, async ({ page }) => {
    // 1. Pergi ke halaman kelompok
    await page.goto('kelompok');
    await page.waitForLoadState('networkidle');
    await page.waitForSelector('table', { timeout: 10000 });

    // 2. Cari kelompok pertama dan buka detail
    const detailButton = page.locator('a[title="Lihat Detail Data"]').first();

    if (await detailButton.count() === 0) {
      test.skip(true, 'Tidak ada data kelompok untuk ditest');
      return;
    }

    await detailButton.click();
    await page.waitForLoadState('networkidle');
    await page.waitForSelector('.box-body', { timeout: 10000 });

    // 3. Cari gambar logo
    const logoImage = page.locator('img.img-thumbnail[alt*="Logo"]');
    await expect(logoImage).toBeVisible();

    // 4. Ambil src gambar
    const imageSrc = await logoImage.getAttribute('src');
    
    // 5. Verifikasi gambar bisa diakses dengan request HTTP
    const response = await page.request.get(imageSrc!);
    expect(response.status(), 'Request gambar harus berhasil (status 200)').toBe(200);
    
    // 6. Verifikasi content-type adalah gambar
    const contentType = response.headers()['content-type'];
    expect(contentType, 'Content-type harus berupa gambar').toMatch(/^image\//);
  });
});
