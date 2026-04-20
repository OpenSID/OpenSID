import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
    storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Tidak Tampil List daftar penduduk di Pendaftaran Layanan Mandiri #11045', () => {
    test('fix: perbaiki Tidak Tampil List daftar penduduk di Pendaftaran Layanan Mandiri', {
        annotation: {
            type: 'issue',
            description: 'https://github.com/OpenSID/OpenSID/issues/11045',
        },
    }, async ({ page }) => {
        // Navigate ke halaman Pendaftar Layanan Mandiri
        await page.goto('/mandiri');

        // Pastikan judul halaman tampil
        await expect(page.getByRole('heading', { name: /Pendaftar Layanan Mandiri/i })).toBeVisible();

        // Tunggu DataTables selesai memuat
        const dataTableInfo = page.locator('#tabeldata_info');
        await expect(dataTableInfo).toBeVisible();
        await expect(dataTableInfo).toContainText(/Menampilkan|Showing/i);

        // Pastikan ada baris data di tabel (minimal 1 row) dan bukan pesan "Tidak ada data"
        const firstRow = page.locator('#tabeldata tbody tr').first();
        await expect(firstRow).toBeVisible();
        await expect(firstRow).not.toContainText(/Tidak ada data|No data|Tidak ada catatan/i);

        // Verifikasi kolom NIK tampil dengan benar
        const nikCell = page.locator('#tabeldata tbody tr:first-child td:nth-child(3)');
        await expect(nikCell).toBeVisible();
        await expect(nikCell).not.toHaveText('');

        // Verifikasi kolom Nama tampil dengan benar
        const namaCell = page.locator('#tabeldata tbody tr:first-child td:nth-child(4)');
        await expect(namaCell).toBeVisible();
        await expect(namaCell).not.toHaveText('');

        // Verifikasi kolom Status tampil dengan benar
        const statusCell = page.locator('#tabeldata tbody tr:first-child td:nth-child(7)');
        await expect(statusCell).toBeVisible();

        // Verifikasi kolom Aksi tersedia
        const actionCell = page.locator('#tabeldata tbody tr:first-child td:nth-child(2)');
        await expect(actionCell).toBeVisible();
        const actionButton = actionCell.locator('button').first();
        await expect(actionButton).toBeVisible();

        // Test filter status
        // Set status filter ke "1" (Aktif)
        const statusSelect = page.locator('#status');
        if (await statusSelect.isVisible()) {
            await statusSelect.selectOption('1');
            
            // Tunggu DataTables redraw setelah filter
            await page.waitForTimeout(500);
            await expect(dataTableInfo).toBeVisible();
        }

        // Verifikasi bahwa tabel masih menampilkan data setelah filter
        const firstRowAfterFilter = page.locator('#tabeldata tbody tr').first();
        await expect(firstRowAfterFilter).toBeVisible();
        await expect(firstRowAfterFilter).not.toContainText(/Tidak ada data|No data|Tidak ada catatan/i);
    });
});
