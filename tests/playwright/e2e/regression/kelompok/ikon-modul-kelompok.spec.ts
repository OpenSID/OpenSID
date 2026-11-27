// Test: Ikon modul kelompok
import { test, expect } from '../../../utils/regressionTest';


test.describe('Kelompok Module Icons', () => {
  test.beforeEach(async ({ page }) => {
    await page.goto('kelompok');
    await page.waitForLoadState('networkidle');
    await page.waitForSelector('table tbody tr', { timeout: 10000 });
  });

  test('should display icons on tambah anggota kelompok options', async ({ page }) => {
    // Klik tombol Rincian Data pada baris pertama
    const detailButton = page.locator('table tbody tr:first-child a[title="Rincian Data"]');
    await expect(detailButton).toBeVisible({ timeout: 10000 });
    await detailButton.click();
    await page.waitForLoadState('networkidle');

    // Klik dropdown-toggle dari split button "Tambah" — menggunakan selector spesifik
    // agar tidak mengambil <a> tersembunyi di dalam <ul class="dropdown-menu">
    const tambahToggle = page.locator('a.dropdown-toggle:has-text("Tambah")').first();
    await expect(tambahToggle).toBeVisible({ timeout: 10000 });
    await tambahToggle.click();

    // Tunggu dropdown terbuka
    await page.waitForTimeout(300);

    // Verifikasi opsi "Tambah Satu Anggota" tersedia dan memiliki icon Font Awesome
    const tambahSatuOption = page.locator('.dropdown-menu a[title*="Tambah Satu Anggota"]').first();
    await expect(tambahSatuOption).toBeVisible({ timeout: 5000 });
    const icon1 = tambahSatuOption.locator('i.fa');
    await expect(icon1).toBeAttached();

    // Verifikasi opsi "Tambah Beberapa Anggota" tersedia dan memiliki icon Font Awesome
    const tambahBeberapaOption = page.locator('.dropdown-menu a[title*="Tambah Beberapa Anggota"]').first();
    await expect(tambahBeberapaOption).toBeVisible({ timeout: 5000 });
    const icon2 = tambahBeberapaOption.locator('i.fa');
    await expect(icon2).toBeAttached();
  });

  test('should verify icon visibility on dropdown menu', async ({ page }) => {
    // Klik tombol Rincian Data pada baris pertama
    const detailButton = page.locator('table tbody tr:first-child a[title="Rincian Data"]');
    await expect(detailButton).toBeVisible({ timeout: 10000 });
    await detailButton.click();
    await page.waitForLoadState('networkidle');

    // Buka dropdown split button "Tambah"
    const tambahToggle = page.locator('a.dropdown-toggle:has-text("Tambah")').first();
    await expect(tambahToggle).toBeVisible({ timeout: 10000 });
    await tambahToggle.click();
    await page.waitForTimeout(300);

    // Dropdown-menu harus terbuka dan berisi minimal 2 opsi
    // Pakai selector spesifik ke btn-group yang memiliki toggle "Tambah"
    const dropdownMenu = page.locator('.btn-group:has(a.btn-success.dropdown-toggle) .dropdown-menu');
    await expect(dropdownMenu).toBeVisible({ timeout: 5000 });

    const options = dropdownMenu.locator('li a');
    const optionCount = await options.count();
    expect(optionCount).toBeGreaterThanOrEqual(2);

    // Setiap opsi harus memiliki icon Font Awesome
    for (let i = 0; i < Math.min(2, optionCount); i++) {
      const icon = options.nth(i).locator('i.fa');
      await expect(icon).toBeAttached();
    }
  });
});
