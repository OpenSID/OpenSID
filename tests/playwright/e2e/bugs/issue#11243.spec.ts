import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Ketidaksesuaian jumlah rekapitulasi KK antara Laporan Bulanan dan Buku Administrasi Penduduk #11243', () => {
    test('fix: Ketidaksesuaian jumlah rekapitulasi KK antara Laporan Bulanan dan Buku Administrasi Penduduk #11243', {
        annotation: {
            type: 'issue',
            description: 'https://github.com/OpenSID/OpenSID/issues/11243',
        },
    }, async ({ page }) => {
        // 1. Pergi ke halaman Buku Rekapitulasi Jumlah Penduduk
        await page.goto('bumindes_penduduk_rekapitulasi');
        await page.waitForLoadState('networkidle');

        // Pastikan tidak ada fatal error, type error, or PHP error
        const errorMessages = page.locator('text=/TypeError|SyntaxError|Parse error|Syntax error|Fatal error/i');
        await expect(errorMessages).not.toBeVisible();

        // 2. Pastikan tabeldata dimuat
        await page.waitForSelector('table#tabeldata', { timeout: 15000 });
        
        // Memastikan tabel memiliki baris data (bukan sekadar loading/empty)
        const tbodyRows = page.locator('table#tabeldata tbody tr');
        await expect(tbodyRows.first()).toBeVisible();

        // 3. Verifikasi header / halaman utama tampil dengan benar
        await expect(page.getByRole('heading', { name: 'Buku Administrasi Penduduk' })).toBeVisible();
    });
});
