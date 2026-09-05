import { test, expect } from '@playwright/test';

/**
 * Skenario 1 (Fase 2, OpenSID/OpenSID#11931): halaman depan render, login
 * admin sukses. Ini persis yang akan gagal SENYAP kalau perbaikan urutan-boot
 * Umum (Fase 1: App\Services\Laravel::boot() try/catch per-provider,
 * EventServiceProvider::boot() registrasi langsung, AppServiceProvider::boot()
 * Model::setEventDispatcher() eksplisit) salah/kurang -- gejalanya BUKAN
 * exception yang jelas, tapi event `Login` diam-diam tidak tersambung ke sesi
 * CI3 `isAdmin`, sehingga login "berhasil" tapi setiap halaman admin berikutnya
 * redirect balik ke halaman login seolah belum login.
 */
test.describe('Smoke boot', () => {
  test('halaman depan render tanpa error', async ({ page }) => {
    const response = await page.goto('/');
    expect(response?.status()).toBeLessThan(400);
    await expect(page).not.toHaveTitle(/error/i);
  });

  test('login admin sukses dan tersambung ke sesi CI3 isAdmin', async ({ page }) => {
    await page.goto('/index.php/siteman');
    await page.getByPlaceholder('Nama pengguna').fill(process.env.PLAYWRIGHT_AUTH_USERNAME || 'admin');
    await page.getByPlaceholder('Kata sandi').fill(process.env.PLAYWRIGHT_AUTH_PASSWORD || 'Admin$123');
    await page.getByRole('button', { name: 'Masuk' }).first().click();

    // Fixture desa punya kode_desa kosong -> redirect ke Identitas Desa adalah
    // bukti PALING KUAT bahwa login sukses: halaman itu sendiri ada di balik
    // middleware admin (butuh sesi isAdmin). Kalau event Login gagal
    // tersambung ke sesi CI3, request ini akan balik ke form login, bukan ke
    // halaman admin manapun (identitas_desa ATAU dashboard siteman biasa,
    // tergantung kelengkapan data desa fixture).
    await expect(page).toHaveURL(/siteman|identitas_desa/);
    await expect(page.getByPlaceholder('Nama pengguna')).toHaveCount(0);
  });
});
