import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
    storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Penyesuaian Judul Cetak dan Unduhan Data Riwayat Mutasi Penduduk dimana Lahir tidak ada #11092', () => {
    test('fix: perbaikan Penyesuaian Judul Cetak dan Unduhan Data Riwayat Mutasi Penduduk dimana Lahir tidak ada', {
        annotation: {
            type: 'issue',
            description: 'https://github.com/OpenSID/OpenSID/issues/11092',
        },
    }, async ({ page }) => {
        await page.goto('/penduduk_log/cetak');

        const judulCetak = page.locator('h3');
        await expect(judulCetak).toBeVisible();
        await expect(judulCetak).toContainText(/LAHIR/);
    });
});
