import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
    storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Perbedaan hasil rekapitulasi di desa angseri karena ketidak sesuaian data kelahiran/keluarga baru bulan ini #11203', () => {
    test('fix: perbaikan perbedaan hasil rekapitulasi di desa angseri karena ketidak sesuaian data kelahiran/keluarga baru bulan ini', {
        annotation: {
            type: 'issue',
            description: 'https://github.com/OpenSID/OpenSID/issues/11203',
        },
    }, async ({ page }) => {
        // 1. Ambil data awal di Laporan Bulanan (bulan ini)
        await page.goto('laporan');
        await page.waitForSelector('table');

        // Pilih baris yang mengandung teks 'Penduduk/Keluarga awal bulan ini'
        // Kolom ke-9 (index 8) adalah Total Jiwa (L+P)
        const rowAwal = page.locator('table tr').filter({ hasText: 'Penduduk/Keluarga awal bulan ini' });
        const awalSebelum = parseInt(await rowAwal.locator('td').nth(8).innerText()) || 0;

        const rowLahir = page.locator('table tr').filter({ hasText: 'Kelahiran/Keluarga baru bulan ini' });
        const lahirSebelum = parseInt(await rowLahir.locator('td').nth(8).innerText()) || 0;

        // 2. Tambah penduduk lahir
        // Kita gunakan tanggal peristiwa bulan lalu untuk memicu kondisi bug
        const now = new Date();
        const lastMonth = new Date(now.getFullYear(), now.getMonth() - 1, now.getDate());
        const day = String(lastMonth.getDate()).padStart(2, '0');
        const month = String(lastMonth.getMonth() + 1).padStart(2, '0');
        const year = lastMonth.getFullYear();
        const tglPeristiwa = `${day}-${month}-${year}`; // Format dd-mm-yyyy sesuai form OpenSID

        await page.goto('penduduk/form_peristiwa/1');

        // Isi form kelahiran
        await page.getByRole('textbox', { name: 'Nama Lengkap (Tanpa Gelar)' }).fill('BAYI TEST FIX 11203');

        // Pilih KK (asumsi ada data KK)
        await page.locator('select[name="id_kk"]').selectOption({ index: 1 });

        // Hubungan Keluarga: Anak (kode 4)
        await page.locator('select[name="kk_level"]').selectOption('4');

        // Jenis Kelamin: Laki-laki (kode 1)
        await page.locator('select[name="sex"]').selectOption('1');

        await page.getByRole('textbox', { name: 'Tempat Lahir' }).fill('TEMPAT TEST');
        await page.getByRole('textbox', { name: 'Tanggal Lahir' }).fill(tglPeristiwa);

        // Simpan
        await page.getByRole('button', { name: ' Simpan' }).click();

        // Tunggu redirect kembali ke daftar penduduk
        await expect(page).toHaveURL(/penduduk/);

        // 3. Verifikasi di Laporan Bulanan
        await page.goto('laporan');
        await page.waitForSelector('table');

        const awalSesudah = parseInt(await page.locator('table tr').filter({ hasText: 'Penduduk/Keluarga awal bulan ini' }).locator('td').nth(8).innerText()) || 0;
        const lahirSesudah = parseInt(await page.locator('table tr').filter({ hasText: 'Kelahiran/Keluarga baru bulan ini' }).locator('td').nth(8).innerText()) || 0;

        // ASSERTION:
        // Sebelum diperbaiki, 'awalSesudah' akan bertambah karena tgl_peristiwa berada di bulan lalu.
        // Setelah diperbaiki (menggunakan tgl_lapor), 'awalSesudah' harus tetap sama dengan 'awalSebelum'.
        expect(awalSesudah, 'Jumlah penduduk awal tidak boleh berubah jika lapor di bulan berjalan').toBe(awalSebelum);

        // Jumlah kelahiran harus bertambah 1
        expect(lahirSesudah, 'Jumlah kelahiran harus bertambah 1').toBe(lahirSebelum + 1);
    });
});
