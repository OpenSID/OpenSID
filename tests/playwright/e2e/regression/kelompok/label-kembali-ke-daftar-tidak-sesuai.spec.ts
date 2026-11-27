// Issue: #11028 — Label tombol kembali ke daftar kelompok tidak sesuai dengan aksi
// https://github.com/OpenSID/OpenSID/issues/11028
import { test, expect } from '../../../utils/regressionTest';


test.describe('Bug/error: Label Tombol “Kembali ke Daftar Kelompok Di Desa” pada Form Tambah/Ubah Dokumen Kelompok Tidak Sesuai dengan Aksi #11028', () => {
    test('fix: perbaiki Label Tombol “Kembali ke Daftar Kelompok Di Desa” pada Form Tambah/Ubah Dokumen Kelompok Tidak Sesuai dengan Aksi', {
        annotation: {
            type: 'issue',
            description: 'https://github.com/OpenSID/OpenSID/issues/11028',
        },
    }, async ({ page }) => {
        await page.goto('/kelompok/dokumen-form?id_kelompok=15');

        // Pastikan heading berisi "Pengaturan Dokumen Kelompok"
        await expect(page.getByRole('heading', { name: /Pengaturan Dokumen Kelompok/i })).toBeVisible();

        // Pastikan tombol kembali berisi teks "Kembali ke Daftar Dokumen Kelompok"
        await expect(page.getByRole('link', { name: /Kembali ke Daftar Dokumen Kelompok/i })).toBeVisible();

        // Pastikan label lama yang salah sudah tidak muncul
        await expect(page.getByText(/Di Desa/i)).not.toBeVisible();
    });
});
