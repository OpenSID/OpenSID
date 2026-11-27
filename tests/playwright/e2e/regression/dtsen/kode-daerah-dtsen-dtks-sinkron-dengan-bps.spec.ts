// Issue: #11265 — Kode daerah pada DTSEN/DTKS tidak sesuai BPS di Identitas Desa
// https://github.com/OpenSID/OpenSID/issues/11265
import { test, expect } from '../../../utils/regressionTest';

test.describe('Bug/error: Kode daerah pada dtsen tidak sesuai, cek kode desa, kecamatan, kabupaten, dan provinsi nya #11265', () => {
    test('fix: Kode daerah pada DTSEN dan DTKS tersinkronisasi dinamis sesuai BPS di Identitas Desa', {
        annotation: {
            type: 'issue',
            description: 'https://github.com/OpenSID/OpenSID/issues/11265',
        },
    }, async ({ page }) => {
        // 1. Kunjungi halaman Identitas Desa
        await page.goto('/identitas_desa');

        // 2. Dapatkan Kode BPS Desa/Kelurahan dari tabel identitas
        const bpsRow = page.locator('tr', { hasText: 'Kode BPS' });
        await expect(bpsRow).toBeVisible();
        const bpsCodeFullText = await bpsRow.locator('td').nth(2).innerText();
        const bpsCode = bpsCodeFullText.trim();

        // Pastikan kode BPS memiliki panjang yang cukup (minimal 10 digit) untuk diparse
        expect(bpsCode.length).toBeGreaterThanOrEqual(10);

        // Hitung substring ekspektasi kode BPS (Provinsi, Kabupaten, Kecamatan, Desa)
        const expectedProv = bpsCode.substring(0, 2);
        const expectedKab = bpsCode.substring(2, 4);
        const expectedKec = bpsCode.substring(4, 7);
        const expectedDesa = bpsCode.substring(7, 10);

        // 3. Kunjungi halaman form pendataan DTSEN (gunakan data ID 2)
        await page.goto('/dtsen/pendataan/form/2');

        // Pastikan form terload dengan benar
        await page.waitForSelector('input[name="kode_provinsi"]');

        // Verifikasi kode daerah pada form DTSEN cocok dengan pecahan Kode BPS
        await expect(page.locator('input[name="kode_provinsi"]')).toHaveValue(expectedProv);
        await expect(page.locator('input[name="kode_kabupaten"]')).toHaveValue(expectedKab);
        await expect(page.locator('input[name="kode_kecamatan"]')).toHaveValue(expectedKec);
        await expect(page.locator('input[name="kode_desa"]')).toHaveValue(expectedDesa);

        // 4. Kunjungi halaman form pendataan DTKS (gunakan data ID 2)
        await page.goto('/dtks/pendataan/form/2');

        // Pastikan form terload dengan benar
        await page.waitForSelector('input[name="kode_provinsi"]');

        // Verifikasi kode daerah pada form DTKS cocok dengan pecahan Kode BPS
        await expect(page.locator('input[name="kode_provinsi"]')).toHaveValue(expectedProv);
        await expect(page.locator('input[name="kode_kabupaten"]')).toHaveValue(expectedKab);
        await expect(page.locator('input[name="kode_kecamatan"]')).toHaveValue(expectedKec);
        await expect(page.locator('input[name="kode_desa"]')).toHaveValue(expectedDesa);
    });
});
