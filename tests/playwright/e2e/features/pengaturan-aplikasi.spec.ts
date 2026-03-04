/**
 * Playwright E2E Tests
 * Fix: perbaikan validasi pengaturan aplikasi
 * Commit: 460292f570d1497688d9c36676765ec73211cad9
 *
 * Perubahan yang diuji:
 * 1. Komponen baru `input-password` — toggle show/hide & hint "Kosongkan jika tidak ingin mengubah Password."
 * 2. `select-boolean` kini memiliki atribut `id` sehingga dapat dijadikan trigger `required_if`.
 * 3. Validasi `required_if`: field email SMTP hanya tampil & wajib ketika notifikasi email aktif.
 * 4. Flag `optional: true`: field `email_smtp_pass` TIDAK mendapat class `required` meski parent aktif.
 * 5. Validasi `required_if`: field Telegram hanya tampil ketika notifikasi Telegram aktif.
 * 6. Jenis `google_recaptcha` berubah dari `boolean` ke `select-boolean` (memiliki atribut id).
 * 7. `tagline_singkat` maxlength berubah dari 10 → 255.
 */

import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
    storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Fix: perbaikan validasi pengaturan aplikasi (commit 460292f570)', () => {

    test.beforeEach(async ({ page }) => {
        await page.goto('setting');
        // Tunggu form selesai dimuat
        await expect(page.locator('#validasi')).toBeVisible();
    });

    // -------------------------------------------------------------------------
    // 1. Komponen input-password: render & toggle show/hide
    // -------------------------------------------------------------------------
    test.describe('Komponen input-password (email_smtp_pass)', () => {
        test('field email_smtp_pass bertipe password dan memiliki tombol show/hide', async ({ page }) => {
            // Field hanya terlihat saat email_notifikasi aktif
            const emailNotifSelect = page.locator('#email_notifikasi');
            await emailNotifSelect.selectOption('1');
            await page.waitForTimeout(300);

            const passwordInput = page.locator('#input_email_smtp_pass');
            await expect(passwordInput).toBeVisible();
            await expect(passwordInput).toHaveAttribute('type', 'password');

            // Tombol toggle show/hide harus ada di sebelah input
            const toggleBtn = page.locator('#form_email_smtp_pass .show-hide-password');
            await expect(toggleBtn).toBeVisible();
        });

        test('tombol show/hide password mengubah type password → text → password', async ({ page }) => {
            const emailNotifSelect = page.locator('#email_notifikasi');
            await emailNotifSelect.selectOption('1');
            await page.waitForTimeout(300);

            const passwordInput = page.locator('#input_email_smtp_pass');
            const toggleBtn = page.locator('#form_email_smtp_pass .show-hide-password');

            // Kondisi awal: type="password"
            await expect(passwordInput).toHaveAttribute('type', 'password');

            // Klik toggle → type="text"
            await toggleBtn.click();
            await expect(passwordInput).toHaveAttribute('type', 'text');

            // Klik lagi → type="password"
            await toggleBtn.click();
            await expect(passwordInput).toHaveAttribute('type', 'password');
        });

        test('muncul teks bantuan "Kosongkan jika tidak ingin mengubah Password." saat ada password tersimpan', async ({ page }) => {
            const emailNotifSelect = page.locator('#email_notifikasi');
            await emailNotifSelect.selectOption('1');
            await page.waitForTimeout(300);

            // Cek container field email_smtp_pass; hint muncul jika data-password="1"
            const passwordContainer = page.locator('#form_email_smtp_pass');
            const passInput = passwordContainer.locator('input[type="password"],input[type="text"]').first();

            // Jika ada password tersimpan (data-password="1"), hint harus tampil
            const dataPassword = await passInput.getAttribute('data-password');
            if (dataPassword === '1') {
                await expect(passwordContainer.locator('p.text-red')).toBeVisible();
                await expect(passwordContainer.locator('p.text-red')).toContainText('Kosongkan jika tidak ingin mengubah Password.');
            } else {
                // Jika tidak ada password tersimpan, hint tidak muncul
                await expect(passwordContainer.locator('p.text-red')).toBeHidden();
            }
        });
    });

    // -------------------------------------------------------------------------
    // 2. select-boolean kini memiliki atribut id
    // -------------------------------------------------------------------------
    test.describe('select-boolean memiliki atribut id', () => {
        test('field google_recaptcha (select-boolean) memiliki id="google_recaptcha"', async ({ page }) => {
            const recaptchaSelect = page.locator('select#google_recaptcha');
            await expect(recaptchaSelect).toBeVisible();
        });

        test('field email_notifikasi (select-boolean) memiliki id="email_notifikasi"', async ({ page }) => {
            const emailNotifSelect = page.locator('select#email_notifikasi');
            await expect(emailNotifSelect).toBeVisible();
        });

        test('field telegram_notifikasi (select-boolean) memiliki id="telegram_notifikasi"', async ({ page }) => {
            const telegramNotifSelect = page.locator('select#telegram_notifikasi');
            await expect(telegramNotifSelect).toBeVisible();
        });
    });

    // -------------------------------------------------------------------------
    // 3 & 4. required_if validasi email SMTP + flag optional
    // -------------------------------------------------------------------------
    test.describe('required_if: field email SMTP tampil/sembunyi berdasarkan email_notifikasi', () => {
        const emailDependentFields = [
            'email_smtp_protocol',
            'email_smtp_host',
            'email_smtp_user',
            'email_smtp_pass',
            'email_smtp_port',
        ];

        test('field email SMTP tersembunyi saat email_notifikasi = 0 (nonaktif)', async ({ page }) => {
            const emailNotifSelect = page.locator('#email_notifikasi');
            await emailNotifSelect.selectOption('0');
            await page.waitForTimeout(300);

            for (const field of emailDependentFields) {
                await expect(page.locator(`#form_${field}`)).toBeHidden();
            }
        });

        test('field email SMTP tampil saat email_notifikasi = 1 (aktif)', async ({ page }) => {
            const emailNotifSelect = page.locator('#email_notifikasi');
            await emailNotifSelect.selectOption('1');
            await page.waitForTimeout(300);

            for (const field of emailDependentFields) {
                await expect(page.locator(`#form_${field}`)).toBeVisible();
            }
        });

        test('field wajib (non-optional) mendapat class required saat email_notifikasi aktif', async ({ page }) => {
            const emailNotifSelect = page.locator('#email_notifikasi');
            await emailNotifSelect.selectOption('1');
            await page.waitForTimeout(300);

            // Field tanpa optional harus mendapat class required
            const requiredFields = [
                'email_smtp_protocol',
                'email_smtp_host',
                'email_smtp_user',
                'email_smtp_port',
            ];

            for (const field of requiredFields) {
                const inputEl = page.locator(`#form_${field}`).locator('input, select, textarea').first();
                await expect(inputEl).toHaveClass(/required/);
            }
        });

        test('email_smtp_pass (optional: true) TIDAK mendapat class required meski email_notifikasi aktif', async ({ page }) => {
            const emailNotifSelect = page.locator('#email_notifikasi');
            await emailNotifSelect.selectOption('1');
            await page.waitForTimeout(300);

            // email_smtp_pass memiliki flag optional:true — tidak boleh required
            const passInput = page.locator('#form_email_smtp_pass').locator('input').first();
            await expect(passInput).not.toHaveClass(/required/);
        });

        test('class required dihapus dari semua field email SMTP saat email_notifikasi dinonaktifkan', async ({ page }) => {
            const emailNotifSelect = page.locator('#email_notifikasi');

            // Aktifkan dulu
            await emailNotifSelect.selectOption('1');
            await page.waitForTimeout(300);

            // Kemudian nonaktifkan
            await emailNotifSelect.selectOption('0');
            await page.waitForTimeout(300);

            for (const field of emailDependentFields) {
                const inputEl = page.locator(`#form_${field}`).locator('input, select, textarea').first();
                await expect(inputEl).not.toHaveClass(/required/);
            }
        });
    });

    // -------------------------------------------------------------------------
    // 5. required_if validasi Telegram
    // -------------------------------------------------------------------------
    test.describe('required_if: field Telegram tampil/sembunyi berdasarkan telegram_notifikasi', () => {
        const telegramDependentFields = [
            'telegram_token',
            'telegram_user_id',
        ];

        test('field Telegram tersembunyi saat telegram_notifikasi = 0 (nonaktif)', async ({ page }) => {
            const telegramNotifSelect = page.locator('#telegram_notifikasi');
            await telegramNotifSelect.selectOption('0');
            await page.waitForTimeout(300);

            for (const field of telegramDependentFields) {
                await expect(page.locator(`#form_${field}`)).toBeHidden();
            }
        });

        test('field Telegram tampil saat telegram_notifikasi = 1 (aktif)', async ({ page }) => {
            const telegramNotifSelect = page.locator('#telegram_notifikasi');
            await telegramNotifSelect.selectOption('1');
            await page.waitForTimeout(300);

            for (const field of telegramDependentFields) {
                await expect(page.locator(`#form_${field}`)).toBeVisible();
            }
        });

        test('field Telegram mendapat class required saat telegram_notifikasi aktif', async ({ page }) => {
            const telegramNotifSelect = page.locator('#telegram_notifikasi');
            await telegramNotifSelect.selectOption('1');
            await page.waitForTimeout(300);

            for (const field of telegramDependentFields) {
                const inputEl = page.locator(`#form_${field}`).locator('input, select, textarea').first();
                await expect(inputEl).toHaveClass(/required/);
            }
        });

        test('class required dihapus dari field Telegram saat telegram_notifikasi dinonaktifkan', async ({ page }) => {
            const telegramNotifSelect = page.locator('#telegram_notifikasi');

            await telegramNotifSelect.selectOption('1');
            await page.waitForTimeout(300);

            await telegramNotifSelect.selectOption('0');
            await page.waitForTimeout(300);

            for (const field of telegramDependentFields) {
                const inputEl = page.locator(`#form_${field}`).locator('input, select, textarea').first();
                await expect(inputEl).not.toHaveClass(/required/);
            }
        });
    });

    // -------------------------------------------------------------------------
    // 6. google_recaptcha berubah dari boolean ke select-boolean
    // -------------------------------------------------------------------------
    test.describe('google_recaptcha menggunakan komponen select-boolean', () => {
        test('google_recaptcha dirender sebagai elemen <select> (bukan <input>)', async ({ page }) => {
            // Harus berupa select, bukan input tersembunyi atau radio
            const el = page.locator('#google_recaptcha');
            await expect(el).toBeVisible();

            const tagName = await el.evaluate((node) => node.tagName.toLowerCase());
            expect(tagName).toBe('select');
        });

        test('google_recaptcha memiliki opsi Aktif dan Tidak Aktif', async ({ page }) => {
            const selectEl = page.locator('#google_recaptcha');
            const options = await selectEl.locator('option').allTextContents();
            // select-boolean menggunakan StatusEnum::all() — minimal ada 2 opsi
            expect(options.length).toBeGreaterThanOrEqual(2);
        });
    });

    // -------------------------------------------------------------------------
    // 7. tagline_singkat maxlength berubah dari 10 → 255
    // -------------------------------------------------------------------------
    test.describe('tagline_singkat: maxlength diperbarui ke 255', () => {
        test('field tagline_singkat memiliki maxlength="255"', async ({ page }) => {
            const taglineInput = page.locator('[name="tagline_singkat"]');
            await expect(taglineInput).toHaveAttribute('maxlength', '255');
        });

        test('tagline_singkat menerima input lebih dari 10 karakter', async ({ page }) => {
            const taglineInput = page.locator('[name="tagline_singkat"]');
            const testValue = 'Tagline panjang yang lebih dari 10 huruf';
            await taglineInput.fill(testValue);
            await expect(taglineInput).toHaveValue(testValue);
        });
    });

    // -------------------------------------------------------------------------
    // 8. required_if validasi Login OTP
    // -------------------------------------------------------------------------
    test.describe('required_if: field OTP tampil/sembunyi berdasarkan login_otp', () => {
        const otpDependentFields = [
            'otp_expiry_minutes',
            'otp_resend_cooldown',
            'otp_max_trials',
        ];

        test('field OTP tersembunyi saat login_otp = 0 (nonaktif)', async ({ page }) => {
            const otpSelect = page.locator('#login_otp');
            await otpSelect.selectOption('0');
            await page.waitForTimeout(300);

            for (const field of otpDependentFields) {
                await expect(page.locator(`#form_${field}`)).toBeHidden();
            }
        });

        test('field OTP tampil saat login_otp = 1 (aktif)', async ({ page }) => {
            const otpSelect = page.locator('#login_otp');
            await otpSelect.selectOption('1');
            await page.waitForTimeout(300);

            for (const field of otpDependentFields) {
                await expect(page.locator(`#form_${field}`)).toBeVisible();
            }
        });

        test('field OTP mendapat class required saat login_otp aktif', async ({ page }) => {
            const otpSelect = page.locator('#login_otp');
            await otpSelect.selectOption('1');
            await page.waitForTimeout(300);

            for (const field of otpDependentFields) {
                const inputEl = page.locator(`#form_${field}`).locator('input, select, textarea').first();
                await expect(inputEl).toHaveClass(/required/);
            }
        });
    });

    // -------------------------------------------------------------------------
    // 9. required_if validasi Masa Aktif Akun
    // -------------------------------------------------------------------------
    test.describe('required_if: field Masa Aktif Akun tampil/sembunyi berdasarkan masa_akun_pengguna', () => {
        const masaAkunDependentFields = [
            'masa_akun_tidak_aktif',
            'jenis_trigger_nonaktifkan_akun',
        ];

        test('field Masa Aktif Akun tersembunyi saat masa_akun_pengguna = 0 (nonaktif)', async ({ page }) => {
            const masaAkunSelect = page.locator('#masa_akun_pengguna');
            await masaAkunSelect.selectOption('0');
            await page.waitForTimeout(300);

            for (const field of masaAkunDependentFields) {
                await expect(page.locator(`#form_${field}`)).toBeHidden();
            }
        });

        test('field Masa Aktif Akun tampil saat masa_akun_pengguna = 1 (aktif)', async ({ page }) => {
            const masaAkunSelect = page.locator('#masa_akun_pengguna');
            await masaAkunSelect.selectOption('1');
            await page.waitForTimeout(300);

            for (const field of masaAkunDependentFields) {
                await expect(page.locator(`#form_${field}`)).toBeVisible();
            }
        });

        test('field Masa Aktif Akun mendapat class required saat masa_akun_pengguna aktif', async ({ page }) => {
            const masaAkunSelect = page.locator('#masa_akun_pengguna');
            await masaAkunSelect.selectOption('1');
            await page.waitForTimeout(300);

            for (const field of masaAkunDependentFields) {
                const inputEl = page.locator(`#form_${field}`).locator('input, select, textarea').first();
                await expect(inputEl).toHaveClass(/required/);
            }
        });
    });
});
