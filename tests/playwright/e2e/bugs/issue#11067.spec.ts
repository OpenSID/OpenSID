import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
    storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('https://github.com/OpenSID/OpenSID/issues/11067', () => {
    test('fix: perbaiki Pada menu DTSEN di pendataan kesehatan seharusnya bisa pilih multiple penyakit', {
        annotation: {
            type: 'issue',
            description: 'https://github.com/OpenSID/OpenSID/issues/11067',
        },
    }, async ({ page }) => {
        await page.goto('/dtsen/pendataan/form/4');
        
        // 1. Klik tombol tab kesehatan anggota pertama untuk membuka modal
        await page.locator('a[data-table="tabel_kesehatan"]').first().click();
        await expect(page.locator('#modal-tab4')).toBeVisible();

        // 2. Pilih "Ya" untuk 2 penyakit berbeda: Hipertensi (2) dan Lainnya (18)
        // Value '1' merepresentasikan "Ya"
        await page.locator('#pilihan_4_430_2').selectOption('1', { force: true });
        await page.locator('#pilihan_4_430_18').selectOption('1', { force: true });
        
        // 3. Simpan Data Kesehatan
        await page.getByRole('button', { name: 'Simpan' }).click();
        
        // 4. Tunggu modal tertutup (berhasil disimpan)
        await expect(page.locator('#modal-tab4')).toBeHidden({ timeout: 10000 });
        
        // 5. Refresh halaman untuk memastikan data termuat dari database
        await page.reload();
        
        // 6. Buka kembali modal kesehatan anggota pertama
        await page.locator('a[data-table="tabel_kesehatan"]').first().click();
        await expect(page.locator('#modal-tab4')).toBeVisible();
        
        // 7. Verifikasi bahwa kedua penyakit masih terpilih sebagai "Ya"
        await expect(page.locator('#pilihan_4_430_2')).toHaveValue('1');
        await expect(page.locator('#pilihan_4_430_18')).toHaveValue('1');
});
