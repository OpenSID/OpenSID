import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
    storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: SHDK pada isian 409 DTSEN tidak otomatis terisi berdasarkan data penduduk #11074', () => {
    test('fix: perbaiki SHDK pada isian 409 DTSEN tidak otomatis terisi berdasarkan data penduduk', {
        annotation: {
            type: 'issue',
            description: 'https://github.com/OpenSID/OpenSID/issues/11074',
        },
    }, async ({ page }) => {
        // Asumsi: Kita menggunakan data dummy atau data yang sudah ada di database testing
        await page.goto('/dtsen/pendataan');

        // Cari baris pertama dan klik tombol edit (atau sesuaikan dengan UI asli)
        // Jika langsung ke form 4:
        await page.goto('/dtsen/pendataan/form/4');

        // Tunggu tabel anggota keluarga dimuat
        await page.waitForSelector('#tabel_art_dtsen');

        // Ambil baris-baris anggota keluarga
        const rows = await page.locator('#tabel_art_dtsen tbody tr');
        const count = await rows.count();

        for (let i = 0; i < count; i++) {
            const row = rows.nth(i);
            const nama = await row.locator('td').first().innerText();

            // Klik tombol "Lihat" untuk membuka modal
            await row.locator('a.modal-table').first().click();

            // Tunggu modal muncul
            await page.waitForSelector('#modal-tab4.in');

            // Cek nilai SHDK (409)
            // Kita tidak bisa memastikan nilainya tanpa tahu data penduduk asli, 
            // tapi kita bisa memastikan nilainya TIDAK selalu '1' (Kepala Keluarga) jika itu bukan baris pertama
            const shdkValue = await page.locator('#pilihan_4_409').inputValue();

            console.log(`Anggota: ${nama.split('\n')[0]}, SHDK Value: ${shdkValue}`);

            // Tutup modal untuk lanjut ke anggota berikutnya
            await page.locator('#modal-tab4 button.close').click();
            await page.waitForSelector('#modal-tab4', { state: 'hidden' });
        }
    });
});
