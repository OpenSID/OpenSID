import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
    storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Tidak ada validasi jenis file (Unrestricted File Upload) pada menu upload foto profil #6129', () => {
    test('fix: perbaikan Tidak ada validasi jenis file (Unrestricted File Upload) pada menu upload foto profil', {
        annotation: {
            type: 'issue',
            description: 'https://github.com/OpenSID/premium/issues/6129',
        },
    }, async ({ page }) => {
        await page.goto('/pengguna');

        // Pastikan berada di tab profil
        await page.click('a[href="#profil"]');

        // Menyiapkan file berbahaya (simulasi PHP)
        const maliciousFile = {
            name: 'shell.php',
            mimeType: 'application/x-php',
            buffer: Buffer.from('<?php echo "script berbahaya"; ?>'),
        };

        // Upload file yang salah
        await page.setInputFiles('#file', maliciousFile);

        // Klik tombol Simpan di tab profil
        // Tombol simpan ada di dalam form#validasi yang ada di tab profil
        await page.locator('#profil button[type="submit"]').click();

        // Berharap ada notifikasi error
        const notifikasi = page.locator('#notifikasi.alert-danger');
        await expect(notifikasi).toBeVisible();
        await expect(notifikasi).toContainText('Format file tidak didukung');
        await expect(notifikasi).toContainText('harap unggah file gambar');
    });
});