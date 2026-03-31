import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
    storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Informasi masa berlaku OTP tidak sesuai antara aplikasi dan email #10977', () => {
    test('fix: perbaiki Informasi masa berlaku OTP tidak sesuai antara aplikasi dan email', {
        annotation: {
            type: 'issue',
            description: 'https://github.com/OpenSID/OpenSID/issues/10977',
        },
    }, async ({ page }) => {
        await page.goto('/pengguna#otp');

        // 1. Ambil nilai otp_expiry_minutes dari halaman pengaturan aplikasi
        await page.goto('/setting');
        await page.waitForLoadState('networkidle');

        const otpExpiryInput = page.locator('input[name="otp_expiry_minutes"]');
        await expect(otpExpiryInput).toBeVisible();

        const otpExpiryValue = await otpExpiryInput.inputValue();
        expect(otpExpiryValue).not.toBe('');

        const expiryMinutes = parseInt(otpExpiryValue, 10);
        expect(expiryMinutes).toBeGreaterThan(0);

        // 2. Kembali ke halaman OTP dan verifikasi tampilan masa berlaku
        await page.goto('/pengguna#otp');
        await page.waitForLoadState('networkidle');

        // 3. Pastikan elemen informasi masa berlaku OTP tampil di halaman
        const otpExpiryInfo = page.locator('text=' + expiryMinutes + ' menit').first();
        await expect(otpExpiryInfo).toBeVisible();

        // 4. Pastikan nilai yang tampil di halaman OTP sesuai dengan pengaturan aplikasi
        //    (bukan hardcoded 5 menit)
        const pageContent = await page.content();
        expect(pageContent).toContain(String(expiryMinutes));

        // 5. Pastikan nilai hardcoded lama (5) tidak mempengaruhi tampilan
        //    jika nilai setting != 5, maka angka 5 tidak boleh muncul sebagai durasi OTP
        if (expiryMinutes !== 5) {
            const hardcodedFiveMinutes = page.locator('[id*="otp"], [class*="otp"]').filter({
                hasText: '5 menit',
            });
            await expect(hardcodedFiveMinutes).toHaveCount(0);
        }

        // 6. Simulasi: ubah nilai otp_expiry_minutes di pengaturan dan verifikasi konsistensi
        await page.goto('/setting');
        await page.waitForLoadState('networkidle');

        // Isi ulang nilai yang sama untuk memastikan tidak ada cache lama
        await otpExpiryInput.fill(String(expiryMinutes));

        const saveButton = page.locator('button[type="submit"], input[type="submit"]').first();
        await saveButton.click();
        await page.waitForLoadState('networkidle');

        // 7. Kembali ke halaman OTP dan pastikan nilai tetap konsisten setelah save
        await page.goto('/pengguna#otp');
        await page.waitForLoadState('networkidle');

        const otpExpiryInfoAfterSave = page.locator('text=' + expiryMinutes + ' menit').first();
        await expect(otpExpiryInfoAfterSave).toBeVisible();
    });
});