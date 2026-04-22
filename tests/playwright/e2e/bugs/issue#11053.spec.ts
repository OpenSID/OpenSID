import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
    storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: tidak ada keterangan/validasi maksimal karakter saat input di pengaturan peta #11053  ', () => {
    test('fix: perbaiki tidak ada keterangan/validasi maksimal karakter saat input di pengaturan peta', {
        annotation: {
            type: 'issue',
            description: 'https://github.com/OpenSID/OpenSID/issues/11053',
        },
    }, async ({ page }) => {
        // 1. Buka form tambah garis
        await page.goto('/garis/form/0');

        // 2. Cek apakah field "nama" memiliki maxlength="50"
        const namaInput = page.locator('input[name="nama"]');
        await expect(namaInput).toHaveAttribute('maxlength', '50');

        // 3. Coba isi dengan data lebih dari 50 karakter
        // Playwright fill() secara default akan terpotong oleh maxlength browser
        const longName = 'A'.repeat(60);
        await namaInput.fill(longName);

        // Verifikasi bahwa input terpotong menjadi 50 karakter (sisi client)
        const inputValue = await namaInput.inputValue();
        expect(inputValue.length).toBe(50);

        // 4. Test sisi server (opsional, jika ingin memaksa pengiriman data lebih dari 50)
        // Kita bisa menggunakan evaluate untuk melewati batasan maxlength browser demi testing
        await namaInput.evaluate((el: HTMLInputElement, val) => el.value = val, longName);

        // Isi field wajib lainnya
        await page.locator('select[name="jenis"]').selectOption({ index: 1 });
        // Tunggu loading kategori (AJAX)
        await page.waitForTimeout(1000);
        await page.locator('select[name="ref_line"]').selectOption({ index: 1 });
        await page.locator('textarea[name="desk"]').fill('Tes deskripsi');

        // Simpan
        await page.click('button[type="submit"]');

        // 5. Verifikasi pesan validasi muncul (karena kita memaksa 60 karakter tadi)
        // Pesan eror CI biasanya muncul di alert
        await expect(page.locator('.alert-danger')).toBeVisible();
        await expect(page.locator('.alert-danger')).toContainText('Nama');
        await expect(page.locator('.alert-danger')).toContainText('50');
    });
});
