import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
    storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Tidak bisa upload file galeri dan gambar tidak tampil di slider #10963', () => {
    test('fix: perbaiki Tidak bisa upload file galeri dan gambar tidak tampil di slider', {
        annotation: {
            type: 'issue',
            description: 'https://github.com/OpenSID/OpenSID/issues/10963',
        },
    }, async ({ page }) => {
        // --- 1. Test Tambah Galeri (URL) dari Google Drive ---
        await page.goto('gallery/form/0');
        await page.fill('input[name="nama"]', 'Test Gambar Google Drive URL');
        await page.selectOption('select[name="jenis"]', '2'); // Pilih jenis: URL
        await page.fill('input[name="url"]', 'https://drive.google.com/file/d/1kmncGf5GYA38xI4DXEFhE-15kTtClY-3/view?usp=sharing');
        await page.click('button:has-text("Simpan")');
        // Pastikan muncul notifikasi sukses
        await expect(page.locator('.alert-success')).toBeVisible();

        // --- 2. Test Tambah Galeri (File) ---
        await page.goto('gallery/form/0'); 
        await page.fill('input[name="nama"]', 'Test Upload File Asli');
        await page.selectOption('select[name="jenis"]', '1'); // Pilih jenis: File
        
        // Buat file gambar GIF 1x1 pixel yang valid (menghindari error konten berbahaya)
        const gifBuffer = Buffer.from('R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==', 'base64');
        await page.locator('input#file').setInputFiles({
            name: 'test_safe.gif',
            mimeType: 'image/gif',
            buffer: gifBuffer
        });
        await page.click('button:has-text("Simpan")');
        await expect(page.locator('.alert-success')).toBeVisible();

        // --- 3. Test Halaman Publik Galeri ---
        // Masuk ke halaman daftar galeri publik
        await page.goto('galeri');
        // Memastikan img dari Google Drive dirender menjadi thumbnail API dengan id khusus
        await expect(page.locator('img[src="https://drive.google.com/thumbnail?id=1kmncGf5GYA38xI4DXEFhE-15kTtClY-3"]')).toBeVisible();
    });
});