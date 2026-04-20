import { test, expect } from '@playwright/test';
import { setupAuth, STORAGE_STATE } from './auth.js';

/**
 * Global setup - jalankan sekali sebelum semua test
 * Melakukan login dan menyimpan session
 */
test.beforeAll(async () => {
    await setupAuth();
});

/**
 * Example test: Cek halaman beranda
 */
test.describe('OpenSID Dashboard', () => {
    test.use({ storageState: STORAGE_STATE });

    test('should load dashboard after login', async ({ page }) => {
        await page.goto(process.env.BASE_URL + '/beranda');

        // Cek elemen sidebar ada
        await expect(page.locator('aside.main-sidebar, .main-sidebar')).toBeVisible();

        // Cek title halaman
        await expect(page).toHaveTitle(/OpenSID/);

        console.log('✅ Dashboard loaded successfully');
    });
});

/**
 * Example test: Cek menu navigasi
 */
test.describe('Navigation Menu', () => {
    test.use({ storageState: STORAGE_STATE });

    test('should have main navigation menu', async ({ page }) => {
        await page.goto(process.env.BASE_URL + '/beranda');

        // Cek menu sidebar
        const sidebar = page.locator('aside.main-sidebar');
        await expect(sidebar).toBeVisible();

        // Cek beberapa menu utama ada
        await expect(page.locator('text=Beranda')).toBeVisible();

        console.log('✅ Navigation menu visible');
    });
});
