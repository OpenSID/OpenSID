import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('gambar tidak tampil saat menggunakan url drive #10738', () => {
  test('fix: perbaiki gambar tidak tampil saat menggunakan url drive', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10738',
    },
 }, async ({ page }) => {
    const albumName = `Album Uji E2E ${Date.now()}`;
    const imageName = `Gambar Uji E2E ${Date.now()}`;
    
    // URL yang akan diuji
    const googleDriveUrl = 'https://drive.google.com/file/d/1HdrQiVDy2vQeD7wv1-Zp9gpMcGhtMqXG/view';
    const googleImgResUrl = 'https://www.google.com/imgres?imgurl=https%3A%2F%2Fupload.wikimedia.org%2Fwikipedia%2Fcommons%2F4%2F47%2FPNG_transparency_demonstration_1.png';
    const directImageUrl = 'https://upload.wikimedia.org/wikipedia/commons/4/47/PNG_transparency_demonstration_1.png';

    // 1. Navigasi ke halaman galeri dan buat album baru
    await page.goto('gallery');
    await expect(page).toHaveURL(/.*gallery/);

    await page.click('a:has-text("Tambah Album Baru")');
    await page.fill('input[name="nama"]', albumName);
    await page.click('button[type="submit"]');
    await expect(page.locator('.alert-success')).toBeVisible();

    // Masuk ke album yang baru dibuat
    await page.click(`a:has-text("${albumName}")`);

    // --- Pengujian Kasus 1: URL Google Drive ---
    await page.click('a:has-text("Tambah Gambar Baru")');
    await page.fill('input[name="nama"]', imageName);
    await page.selectOption('select[name="jenis"]', '2'); // Tipe URL
    await page.fill('input[name="url"]', googleDriveUrl);
    await page.click('button[type="submit"]');

    // Verifikasi gambar dari Google Drive tampil di tabel melalui proxy
    await expect(page.locator('.alert-success')).toBeVisible();
    const gdriveImage = page.locator(`tr:has-text("${imageName}") >> td >> img`);
    await expect(gdriveImage).toBeVisible();
    const gdriveSrc = await gdriveImage.getAttribute('src');
    expect(gdriveSrc).toContain('gallery?url=');

    // Verifikasi gambar benar-benar termuat
    const isGdriveImageLoaded = await gdriveImage.evaluate(img => (img as HTMLImageElement).naturalWidth > 0);
    expect(isGdriveImageLoaded).toBe(true);

    // --- Verifikasi Pratinjau di Form Edit ---
    await page.click(`tr:has-text("${imageName}") >> a[title="Ubah Data"]`);
    const editPreviewImage = page.locator('#jenis-url >> img');
    await expect(editPreviewImage).toBeVisible();
    const editPreviewSrc = await editPreviewImage.getAttribute('src');
    expect(editPreviewSrc).toContain('gallery?url=');
    const isEditPreviewLoaded = await editPreviewImage.evaluate(img => (img as HTMLImageElement).naturalWidth > 0);
    expect(isEditPreviewLoaded).toBe(true);

    // --- Pengujian Kasus 2: URL Google Image Search ---
    await page.fill('input[name="url"]', googleImgResUrl);
    await page.click('button[type="submit"]');

    // Verifikasi gambar dari Google Image Search (setelah diproses) tampil
    await expect(page.locator('.alert-success')).toBeVisible();
    const googleImgResImage = page.locator(`tr:has-text("${imageName}") >> td >> img`);
    await expect(googleImgResImage).toBeVisible();
    const googleImgResSrc = await googleImgResImage.getAttribute('src');
    expect(googleImgResSrc).toContain('gallery?url=');
    expect(googleImgResSrc).toContain(encodeURIComponent(directImageUrl));

    const isGoogleImgResLoaded = await googleImgResImage.evaluate(img => (img as HTMLImageElement).naturalWidth > 0);
    expect(isGoogleImgResLoaded).toBe(true);
    
    // --- Pembersihan: Hapus album pengujian ---
    await page.goto('gallery');
    await page.click(`tr:has-text("${albumName}") >> a[data-target="#confirm-delete"]`);
    await page.waitForSelector('#confirm-delete.in', { state: 'visible' });
    await page.click('#confirm-delete >> a:has-text("Hapus")');
    await expect(page.locator('.alert-success')).toBeVisible();
  });
});
