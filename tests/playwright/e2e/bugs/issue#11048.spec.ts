import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
    storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: saat verifikasi telegram berhasil, tombol verifikasi masih tampil jika tidak direfresh #11048', () => {
    test('fix: saat verifikasi telegram berhasil, tombol verifikasi masih tampil jika tidak direfresh #11048', {
        annotation: {
            type: 'issue',
            description: 'https://github.com/OpenSID/OpenSID/issues/11048',
        },
    }, async ({ page }) => {
        // Intercept route pengguna untuk memastikan tombol verifikasi telegram selalu tampil di DOM awal
        await page.route('**/pengguna', async route => {
            const response = await route.fetch();
            let body = await response.text();
            if (!body.includes('id="verif_telegram"')) {
                body = body.replace(
                    '<span class="input-group-btn">', 
                    '<span class="input-group-btn">\n<button type="button" id="verif_telegram" class="btn btn-sm btn-warning btn-block btn-mb-5"><i class="fa fa-share-square"></i>Verifikasi Telegram</button><br id="br_verif_telegram"/>'
                );
            }
            await route.fulfill({
                response,
                body,
                headers: {
                    ...response.headers(),
                }
            });
        });

        // Mock request AJAX kirim OTP
        await page.route('**/pengguna/kirim_otp_telegram*', async route => {
            await route.fulfill({
                status: 200,
                contentType: 'application/json',
                body: JSON.stringify({
                    status: true,
                    message: 'sucess',
                    data: '123456789'
                })
            });
        });

        // Mock request AJAX verifikasi OTP
        await page.route('**/pengguna/verifikasi_telegram*', async route => {
            await route.fulfill({
                status: 200,
                contentType: 'application/json',
                body: JSON.stringify({
                    status: true,
                    message: 'Verifikasi berhasil'
                })
            });
        });

        // 1. Kunjungi halaman pengguna
        await page.goto('/pengguna');

        // 2. Pastikan tombol Verifikasi Telegram terlihat
        const btnVerif = page.locator('#verif_telegram');
        await expect(btnVerif).toBeVisible();

        // 3. Klik tombol Verifikasi Telegram
        await btnVerif.click();

        // 4. Modal SweetAlert muncul untuk input OTP
        const swalInput = page.locator('.swal2-input');
        await expect(swalInput).toBeVisible();

        // 5. Isi sembarang OTP
        await swalInput.fill('123456');

        // 6. Klik tombol Kirim pada SweetAlert
        const btnKirim = page.locator('.swal2-confirm', { hasText: 'Kirim' });
        await btnKirim.click();

        // 7. Modal SweetAlert sukses muncul ("Verifikasi berhasil")
        const swalSuccess = page.locator('.swal2-title', { hasText: 'Verifikasi berhasil' });
        await expect(swalSuccess).toBeVisible();

        // 8. Pastikan tombol Verifikasi Telegram langsung menghilang TANPA reload halaman
        await expect(btnVerif).toBeHidden();
    });
});