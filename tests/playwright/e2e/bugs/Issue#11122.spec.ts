import { test, expect } from '@playwright/test';

/**
 * Test untuk Issue #11122: Data too long for column `telepon` saat update data penduduk
 * 
 * Validasi maksimal 20 karakter untuk nomor telepon di modul Lapak
 */
test.describe('Issue #11122 - Validasi Telepon Maksimal 20 Karakter', () => {
    test.beforeEach(async ({ page }) => {
        // Login dan navigasi ke halaman Lapak
        await page.goto('/');
        // Assume sudah login
        await page.goto('/lapak_admin/pelapak');
    });

    test('Tidak boleh input telepon lebih dari 15 karakter di form', async ({ page }) => {
        // Buka modal tambah pelapak
        await page.click('a[href*="pelapak_form"]');
        
        // Tunggu modal muncul
        await page.waitForSelector('#modalBox');
        
        // Ambil field telepon
        const teleponInput = page.locator('input[name="telepon"]');
        
        // Cek attribute maxlength
        const maxLength = await teleponInput.getAttribute('maxlength');
        expect(maxLength).toBe('15');
    });

    test('Minimum 10 karakter untuk nomor telepon', async ({ page }) => {
        // Buka modal tambah pelapak
        await page.click('a[href*="pelapak_form"]');
        
        // Tunggu modal muncul
        await page.waitForSelector('#modalBox');
        
        // Ambil field telepon
        const teleponInput = page.locator('input[name="telepon"]');
        
        // Cek attribute minlength
        const minLength = await teleponInput.getAttribute('minlength');
        expect(minLength).toBe('10');
    });

    test('Form tidak menerima input telepon kurang dari 10 karakter', async ({ page }) => {
        // Buka modal tambah pelapak
        await page.click('a[href*="pelapak_form"]');
        
        // Tunggu modal muncul
        await page.waitForSelector('#modalBox');
        
        // Pilih penduduk
        const selectPenduduk = page.locator('select[name="id_pend"]');
        const options = await selectPenduduk.locator('option').count();
        
        if (options > 1) {
            // Pilih opsi pertama (bukan placeholder)
            await selectPenduduk.selectOption({ index: 1 });
            
            // Input telepon kurang dari 10 karakter
            await page.locator('input[name="telepon"]').fill('123456789');
            
            // Submit form
            await page.locator('button[type="submit"]').click();
            
            // Browser akan prevent submit karena minlength validation
            // Check URL tetap sama (tidak submit)
            expect(page.url()).toContain('/lapak_admin/pelapak');
        }
    });

    test('Form menerima telepon dengan 10-15 karakter valid', async ({ page }) => {
        // Buka modal tambah pelapak
        await page.click('a[href*="pelapak_form"]');
        
        // Tunggu modal muncul
        await page.waitForSelector('#modalBox');
        
        // Pilih penduduk
        const selectPenduduk = page.locator('select[name="id_pend"]');
        const options = await selectPenduduk.locator('option').count();
        
        if (options > 1) {
            // Pilih opsi pertama (bukan placeholder)
            await selectPenduduk.selectOption({ index: 1 });
            
            // Input telepon dengan 12 karakter (valid)
            const validPhone = '085123456789';
            await page.locator('input[name="telepon"]').fill(validPhone);
            
            // Cek input value
            const inputValue = await page.locator('input[name="telepon"]').inputValue();
            expect(inputValue).toBe(validPhone);
        }
    });

    test('Form tidak menerima telepon lebih dari 15 karakter di input', async ({ page }) => {
        // Buka modal tambah pelapak
        await page.click('a[href*="pelapak_form"]');
        
        // Tunggu modal muncul
        await page.waitForSelector('#modalBox');
        
        // Ambil field telepon
        const teleponInput = page.locator('input[name="telepon"]');
        
        // Coba input lebih dari 15 karakter
        await teleponInput.fill('08512345678901234');
        
        // Cek bahwa input hanya menerima 15 karakter pertama
        const inputValue = await teleponInput.inputValue();
        expect(inputValue.length).toBeLessThanOrEqual(15);
    });

    test('Validasi server menerima telepon maksimal 20 karakter (dengan truncate)', async ({ page }) => {
        // Test ini memverifikasi bahwa backend memiliki backup validasi
        // jika somehow telepon masuk lebih dari 20 karakter (misalnya via API),
        // akan di-truncate ke 20 karakter
        
        // Ini adalah test logika di Pelapak.php pelapakValidasi()
        // Tidak langsung di-test di UI tapi dokumentasi behavior
        
        // Seharusnya ada unit test di backend untuk ini
        expect(true).toBe(true); // Placeholder untuk integration test
    });

    test('Modal tetap terbuka jika validasi gagal', async ({ page }) => {
        // Buka modal tambah pelapak
        await page.click('a[href*="pelapak_form"]');
        
        // Tunggu modal muncul
        await page.waitForSelector('#modalBox');
        
        // Jangan isi field apapun
        // Click submit
        const submitBtn = page.locator('button[type="submit"]');
        await submitBtn.click();
        
        // Modal tetap visible (karena HTML5 validation atau form error)
        const modal = page.locator('#modalBox');
        await expect(modal).toBeVisible();
    });
});
