import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
    storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: pengaturan apbdes yg ditampilkan dihalaman depan, daftar tahunnya harusnya dipilih sesuai dengan data yg ada di keuangan. saat ini bebas input. #10966', () => {
    test('fix: perbaiki pengaturan tahun apbdes', {
        annotation: {
            type: 'issue',
            description: 'https://github.com/OpenSID/OpenSID/issues/10966',
        },
    }, async ({ page }) => {
        await page.goto('/keuangan_manual');

        // Klik tombol dropdown pengaturan (ikona gear/cogs) yang akan memunculkan modal #pengaturan
        await page.click('a[data-target="#pengaturan"]');

        // Pastikan modal pengaturan tampil
        await expect(page.locator('#pengaturan')).toBeVisible();

        // Verifikasi bahwa apbdes_tahun dirender sebagai <select> element berkat perbaikan data migrasi
        await expect(page.locator('select[name="apbdes_tahun"]')).toBeVisible();

        // Verifikasi <select> tersebut memiliki opsi tahun yang diambil otomatis dari tabel keuangan
        const totalOptions = await page.locator('select[name="apbdes_tahun"] option').count();
        expect(totalOptions).toBeGreaterThan(0);
    });
});