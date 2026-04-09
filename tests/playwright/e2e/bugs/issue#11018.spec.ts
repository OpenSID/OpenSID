import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
    storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Data Rincian Lembaga Tidak Tampil di Web #11018', () => {
    test('fix: perbaiki Data Rincian Lembaga Tidak Tampil di Web', {
        annotation: {
            type: 'issue',
            description: 'https://github.com/OpenSID/OpenSID/issues/11018',
        },
    }, async ({ page }) => {
        // Halaman rincian lembaga
        await page.goto('/data-lembaga/pkk-desa');

        // Pastikan judul rincian data tampil (menandakan AJAX detail berhasil)
        await expect(page.getByRole('heading', { name: /Data Lembaga/i })).toBeVisible();
        await expect(page.getByRole('heading', { name: /Rinci Data Lembaga/i })).toBeVisible();
        await expect(page.getByText('PKK DESA')).toBeVisible();

        // Pastikan tabel pengurus tampil dan memuat data (ROSMIATI adalah ketua di data contoh)
        await expect(page.getByRole('heading', { name: /Daftar Pengurus/i })).toBeVisible();
        await expect(page.getByText('ROSMIATI')).toBeVisible();

        // Pastikan tabel anggota (DataTable) tampil
        await expect(page.getByRole('heading', { name: /Daftar Anggota/i })).toBeVisible();

        // Menunggu DataTables selesai memuat dan menampilkan teks info (Bahasa Indonesia)
        // Kita gunakan id yang ada di natra (#table-anggota) atau esensi (#tabel-data)
        const dataTableInfo = page.locator('#tabel-data_info, #table-anggota_info');
        await expect(dataTableInfo).toContainText(/Menampilkan|Showing/i);

        // Pastikan ada baris data di tabel anggota dan bukan pesan "Tidak ada data"
        const firstRow = page.locator('#tabel-data tbody tr, #table-anggota tbody tr').first();
        await expect(firstRow).toBeVisible();
        await expect(firstRow).not.toContainText(/Tidak ada data|No data/i);
    });
});
