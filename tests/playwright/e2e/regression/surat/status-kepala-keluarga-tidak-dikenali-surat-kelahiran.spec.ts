// Issue: #11258 — Status "Bukan Kepala Keluarga" muncul pada form Surat Keterangan Kelahiran padahal biodata penduduk = Kepala Keluarga
// https://github.com/OpenSID/OpenSID/issues/11258
import { test, expect } from '../../../utils/regressionTest';

// AHLUL — penduduk seed (id=2086, NIK=5201142005716996, kk_level=1, sex=1)
// Dipilih secara spesifik agar test tidak bergantung pada urutan hasil pencarian
const AYAH_NIK = '5201142005716996';

test.describe('Bug/error: Status "Bukan Kepala Keluarga" Muncul pada Form Surat Keterangan Kelahiran Padahal Biodata Penduduk = Kepala Keluarga #11258', () => {
    test('fix: Penduduk status KEPALA KELUARGA dikenali dengan benar di form Surat Keterangan Kelahiran', {
        annotation: {
            type: 'issue',
            description: 'https://github.com/OpenSID/OpenSID/issues/11258',
        },
    }, async ({ page }) => {
        // 1. Pergi ke halaman form Surat Keterangan Kelahiran
        await page.goto('/surat/form/sistem-surat-keterangan-kelahiran');

        // Tunggu form dimuat
        await page.waitForSelector('form#validasi');

        // 2. Cari dan pilih "Data Ayah" yang merupakan kepala keluarga.
        // Select2 menyembunyikan <select> asli — klik span Select2 berdasarkan aria-labelledby.
        // aria-labelledby="select2-ayahnik-pd-container" selalu ada pada widget ayah.
        // Gunakan NIK AHLUL (seed, kk_level=1) agar tidak bergantung pada urutan hasil
        await page.locator('span[aria-labelledby*="ayah"]').click();
        await page.locator('.select2-search__field').fill(AYAH_NIK);
        await page.waitForTimeout(1000); // Tunggu hasil pencarian AJAX

        const results = page.locator('.select2-results__option');
        if (await results.count() > 0) {
            // Pilih hasil pertama (harus AHLUL karena NIK spesifik)
            await results.first().click();

            // Tunggu AJAX selesai — cukup menunggu div ter-attach, bukan visible,
            // karena div bisa tetap tersembunyi jika penduduk tidak punya id_kk.
            await page.waitForSelector('#kategori-ayah .data_penduduk_desa', { state: 'attached', timeout: 15000 });

            // 3. Verifikasi bahwa pesan validasi merah "Penduduk yang dipilih bukan KEPALA KELUARGA"
            // TIDAK muncul di bawah "DATA AYAH"
            const warningLabel = page.locator('#kategori-ayah .text-red', {
                hasText: 'Penduduk yang dipilih bukan KEPALA KELUARGA',
            });
            await expect(warningLabel).not.toBeVisible({ timeout: 15000 });
        } else {
            console.log('Tidak ada data penduduk yang cocok dengan pencarian.');
        }
    });
});
