// Issue: #6394 — Pesan error validasi tidak tampil pada proses login
// https://github.com/OpenSID/premium/issues/6394
//
// Bug: withErrors() menyimpan error ke CI3 session flash, tetapi session flash
// tidak ter-commit ke disk sebelum redirect (race condition PHP-FPM). Akibatnya
// halaman login tidak menampilkan pesan error validasi.
import { test, expect } from '@playwright/test';

test.describe('issue 6394: pesan error validasi tampil di halaman login', () => {
    test('pesan error tampil saat kredensial salah', async ({ page }) => {
        await page.goto('siteman');
        await page.getByPlaceholder('Nama pengguna').fill('admin');
        await page.getByPlaceholder('Kata sandi').fill('password_salah_xyz_12345');
        await page.getByRole('button', { name: 'Masuk' }).first().click();

        // Setelah redirect kembali ke /siteman, pesan error harus tampil
        await expect(page.locator('.alert.alert-danger')).toBeVisible({ timeout: 10000 });
    });
});
