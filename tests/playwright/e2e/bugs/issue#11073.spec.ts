import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
    storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Isian memiliki kartu Identitas pada isian 411 di DTSEN tidak sesuai #11075', () => {
    test('fix: perbaiki Isian memiliki kartu Identitas pada isian 411 di DTSEN tidak sesuai', {
        annotation: {
            type: 'issue',
            description: 'https://github.com/OpenSID/OpenSID/issues/11075',
        },
    }, async ({ page }) => {
        await page.goto('/dtsen/pendataan/form/4');

        // Tunggu tabel dimuat
        await expect(page.locator('#tabel_art_dtsen')).toBeVisible();

        // Ambil semua baris anggota keluarga
        const rows = page.locator('#tabel_art_dtsen tbody tr');
        const rowCount = await rows.count();

        // Kita tes maksimal 2 anggota untuk memastikan variasi umur jika ada
        for (let i = 0; i < Math.min(rowCount, 2); i++) {
            const row = rows.nth(i);
            await row.locator('text=Lihat').click();

            // Verifikasi modal terbuka
            await expect(page.locator('#modal-tab4')).toBeVisible();

            // Verifikasi field 411 TIDAK disabled (Ini inti perbaikannya)
            const pilihan411 = page.locator('#pilihan_4_411');
            await expect(pilihan411).not.toHaveAttribute('disabled');

            // Cek konsistensi data berdasarkan umur di title
            const titleText = await page.locator('#title_art').innerText();
            const ageMatch = titleText.match(/(\d+)\s+Tahun/);

            if (ageMatch) {
                const age = parseInt(ageMatch[1]);
                const selectedValues = await pilihan411.evaluate((el: HTMLSelectElement) => {
                    return Array.from(el.selectedOptions).map(opt => opt.value);
                });

                if (age < 17) {
                    // Untuk anak-anak (< 17), harus otomatis terpilih Akta (1) dan KIA (2)
                    expect(selectedValues).toContain('1');
                    expect(selectedValues).toContain('2');
                } else {
                    // Untuk dewasa (>= 17), harus otomatis terpilih KTP (4)
                    expect(selectedValues).toContain('4');
                }
            }

            // Tutup modal untuk lanjut ke anggota berikutnya
            await page.locator('#modal-tab4 .close').click();
            await page.waitForTimeout(500); // Tunggu animasi tutup modal
        }
    });
});
