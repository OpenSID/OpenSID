import { test, expect } from '@playwright/test';

test.describe('Issue #11123: Fix query menampilkan daftar produk di layanan mandiri', () => {
    test('Daftar produk dapat ditampilkan dengan benar di layanan mandiri warga', async ({ page }) => {
        // Navigasi ke halaman layanan mandiri produk
        await page.goto('http://opensid.test/index.php/layanan-mandiri/produk', { waitUntil: 'networkidle' });

        // Tunggu tabel produk dimuat
        await page.waitForSelector('table#tabel-produk', { timeout: 10000 });

        // Verifikasi tabel ada
        const table = page.locator('table#tabel-produk');
        await expect(table).toBeVisible();

        // Tunggu DataTable selesai render
        await page.waitForFunction(() => {
            const dt = (window as any).jQuery && (window as any).jQuery('#tabel-produk').DataTable;
            return dt && dt() && dt().page !== undefined;
        }, { timeout: 10000 });

        // Ambil jumlah row yang ditampilkan
        const rowCount = await page.locator('table#tabel-produk tbody tr').count();

        // Jika ada data, verifikasi struktur tabel
        if (rowCount > 0) {
            // Cek kolom header ada
            const headers = await page.locator('table#tabel-produk thead th').count();
            expect(headers).toBeGreaterThan(0);

            // Cek row pertama memiliki data
            const firstRow = page.locator('table#tabel-produk tbody tr').first();
            await expect(firstRow).toBeVisible();

            // Verifikasi info paginasi (menampilkan X sampai Y dari Z entri)
            const paginationInfo = page.locator('.dataTables_info');
            const infoText = await paginationInfo.textContent();
            expect(infoText).toBeTruthy();
        } else {
            // Jika tidak ada data, verifikasi info menampilkan "Menampilkan 0 sampai 0 dari 0 entri"
            const paginationInfo = page.locator('.dataTables_info');
            const infoText = await paginationInfo.textContent();
            expect(infoText).toContain('0');
        }
    });
});
