import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
    storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Penandatanganan di statistik kependudukan tidak mengikuti pamong/staf yang dipilih pada saat memilih penandatanganan #11152', () => {
    test('fix: Penandatanganan di statistik kependudukan tidak mengikuti pamong/staf yang dipilih pada saat memilih penandatanganan #11152', {
        annotation: {
            type: 'issue',
            description: 'https://github.com/OpenSID/OpenSID/issues/11152',
        },
    }, async ({ page }) => {
        await page.goto('statistik/penduduk');

        // Buka modal cetak
        await page.getByTitle('Cetak Data').click();
        const modal = page.locator('#modalBox');
        await expect(modal).toBeVisible();

        // Pilih pamong/staf penandatangan selain default (index 2 agar bukan yang pertama/default)
        const pamongTtdSelect = page.locator('select[name="pamong_ttd"]');
        await pamongTtdSelect.selectOption({ index: 2 });

        // Ambil nama pamong yang dipilih
        const selectedOptionText = await pamongTtdSelect.locator('option:checked').textContent() || '';
        const pamongName = selectedOptionText.split(' (')[0].trim().toUpperCase();

        // Isi nomor laporan
        await page.locator('input[name="laporan_no"]').fill('123/STAT/2026');

        // Klik tombol Cetak dan tangkap popup/tab baru
        const [newPage] = await Promise.all([
            page.waitForEvent('popup'),
            page.locator('#btn-ok').click()
        ]);

        await newPage.waitForLoadState();

        // Pastikan nama pamong yang dipilih muncul di halaman cetakan
        await expect(newPage.locator('body')).toContainText(pamongName);

        // Pastikan nomor laporan juga muncul
        await expect(newPage.locator('body')).toContainText('123/STAT/2026');
    });
});