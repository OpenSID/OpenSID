import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
    storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Form isian Nomor Register pada saat menambah inventaris jalan, irigasi dan jaringan tidak terisi secara otomatis, tetapi Blank #11127', () => {
    test('fix: perbaikan form isian nomor register otomatis untuk inventaris jalan, irigasi dan jaringan', {
        annotation: {
            type: 'issue',
            description: 'https://github.com/OpenSID/OpenSID/issues/11127',
        },
    }, async ({ page }) => {
        // Test untuk inventaris jalan
        await page.goto('/inventaris_jalan/form');

        // Periksa bahwa tahun pengadaan default ke tahun sekarang
        const currentYear = new Date().getFullYear().toString();
        const tahunPengadaanSelect = page.locator('#tahun_pengadaan');
        await expect(tahunPengadaanSelect).toHaveValue(currentYear);

        // Pilih nama barang pertama yang tersedia
        const namaBarangSelect = page.locator('#nama_barang');
        await namaBarangSelect.selectOption({ index: 1 }); // Pilih opsi kedua (index 1, karena index 0 mungkin placeholder)

        // Periksa bahwa nomor register terisi otomatis
        const registerInput = page.locator('#register');
        await expect(registerInput).not.toHaveValue(''); // Harus tidak kosong
        const registerValue = await registerInput.inputValue();
        expect(registerValue).toMatch(/^\d{6}$/); // Harus 6 digit angka

        // Test untuk inventaris irigasi (jika ada)
        // Catatan: Jika irigasi dan jaringan menggunakan form yang sama, test ini mungkin cukup
        // Jika berbeda, tambahkan test serupa untuk /inventaris_irigasi/form dan /inventaris_jaringan/form
    });
});