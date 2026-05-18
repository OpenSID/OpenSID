import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
    storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: tidak dapat hapus/nonaktifkan anggota lembaga #11206', () => {
    test('fix: perbaikan visibilitas tombol hapus dan pemfilteran anggota non-aktif', {
        annotation: {
            type: 'issue',
            description: 'https://github.com/OpenSID/OpenSID/issues/11206',
        },
    }, async ({ page }) => {
        // 1. Verifikasi Tombol Hapus Muncul di Admin (Fix #1)
        // Kita asumsikan ada lembaga dengan ID 1
        await page.goto('lembaga/detail/1');
        
        // Cek apakah tombol hapus tersedia (sebelumnya tersembunyi karena bug jml_anggota)
        const hapusButton = page.locator('table#tabel-data').getByRole('link', { name: /hapus/i }).first();
        await expect(hapusButton).toBeVisible();

        // 2. Verifikasi Pemfilteran di Halaman Publik (Fix #2)
        // Ambil nama anggota pertama untuk diuji
        const row = page.locator('table#tabel-data tbody tr').first();
        const namaAnggota = await row.locator('td:nth-child(4)').innerText(); // Kolom nama

        // Edit anggota tersebut dan isi Tanggal SK Pemberhentian
        await row.getByRole('link', { name: /ubah/i }).click();
        await page.fill('input[name="tgl_sk_pemberhentian"]', '01-01-2024');
        await page.getByRole('button', { name: /simpan/i }).click();

        // Cek di halaman publik (asumsi slug lembaga-1)
        await page.goto('data-lembaga/lembaga-1');
        
        // Pastikan nama anggota tersebut TIDAK muncul di daftar pengurus/anggota
        await expect(page.locator('#kelompok-wrapper')).not.toContainText(namaAnggota);
    });
});
