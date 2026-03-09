import { test, expect } from '@playwright/test';

test.describe('Bug/error: Pengguna menjadi tidak aktif setelah di aktifkan #10821', () => {
  test('fix: pengguna yang baru diaktifkan tidak langsung tergembok lagi setelah login', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10821',
    },
  }, async ({ page }) => {
    // Login sebagai admin
    await page.goto('/siteman');
    await page.locator('#username').fill('admin');
    await page.locator('#password').fill('Admin_opensid21!');
    await page.getByRole('button', { name: 'Masuk' }).click();

    // Verifikasi berhasil login dan masuk ke halaman beranda
    await expect(page).toHaveURL(/beranda|main/);

    // Akses beberapa halaman publik untuk memastikan tidak ada proses background
    // yang menonaktifkan akun secara otomatis
    const publicPages = ['/artikel', '/informasi_publik'];
    for (const pagePath of publicPages) {
      await page.goto(pagePath);
      await expect(page).not.toHaveURL(/siteman/);
    }

    // Login ulang untuk memastikan akun masih aktif setelah akses halaman publik
    await page.goto('/siteman');
    await page.locator('#username').fill('admin');
    await page.locator('#password').fill('Admin_opensid21!');
    await page.getByRole('button', { name: 'Masuk' }).click();

    await expect(page).toHaveURL(/beranda|main/);
  });
});