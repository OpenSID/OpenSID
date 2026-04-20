import { test, expect } from '@playwright/test';
import { setupAuth, STORAGE_STATE } from './auth.js';

/**
 * Test untuk mengecek halaman Info Sistem
 * Memastikan semua tab lazy loading berfungsi
 */
test.describe('Info Sistem Page', () => {
    test.use({ storageState: STORAGE_STATE });

    test.beforeAll(async () => {
        await setupAuth();
    });

    test('should load info sistem page without errors', async ({ page }) => {
        // Monitor console errors
        const consoleErrors = [];
        page.on('console', msg => {
            if (msg.type() === 'error') {
                consoleErrors.push(msg.text());
            }
        });

        // Monitor failed requests
        const failedRequests = [];
        page.on('requestfailed', request => {
            failedRequests.push({
                url: request.url(),
                method: request.method(),
                failure: request.failure()
            });
        });

        // Monitor response errors (404, 500, etc)
        const errorResponses = [];
        page.on('response', response => {
            if (response.status() >= 400) {
                errorResponses.push({
                    url: response.url(),
                    status: response.status(),
                    statusText: response.statusText()
                });
            }
        });

        // Buka halaman info_sistem
        console.log('📍 Navigasi ke /info_sistem');
        await page.goto(process.env.BASE_URL + '/info_sistem');

        // Tunggu halaman load
        await page.waitForLoadState('networkidle', { timeout: 10000 });

        // Cek tidak ada error alert
        const errorAlert = page.locator('.alert-danger');
        const hasError = await errorAlert.isVisible().catch(() => false);

        if (hasError) {
            const errorText = await errorAlert.textContent();
            console.log('❌ Error alert found:', errorText);
        }

        // Cek tab navigation ada
        await expect(page.locator('.nav-tabs')).toBeVisible();

        // Report errors
        if (consoleErrors.length > 0) {
            console.log('⚠️  Console Errors:', consoleErrors);
        }

        if (failedRequests.length > 0) {
            console.log('⚠️  Failed Requests:', failedRequests);
        }

        if (errorResponses.length > 0) {
            console.log('⚠️  Error Responses:', errorResponses);
            throw new Error(`Found ${errorResponses.length} error responses`);
        }

        expect(hasError).toBe(false);
        console.log('✅ Info Sistem page loaded successfully');
    });

    test('should load Log Aktivitas tab', async ({ page }) => {
        await page.goto(process.env.BASE_URL + '/info_sistem');

        // Monitor AJAX errors
        const ajaxErrors = [];
        page.on('response', response => {
            if (response.url().includes('datatables-log') && response.status() >= 400) {
                ajaxErrors.push({
                    url: response.url(),
                    status: response.status()
                });
            }
        });

        // Click Log Aktivitas tab
        console.log('🔍 Testing Log Aktivitas tab...');
        await page.click('a[href="#log_aktifitas"]');

        // Tunggu tab content load
        await page.waitForTimeout(2000);

        // Cek tidak ada error
        if (ajaxErrors.length > 0) {
            console.log('❌ AJAX Errors:', ajaxErrors);
            throw new Error('Log Aktivitas tab failed to load');
        }

        console.log('✅ Log Aktivitas tab loaded');
    });

    test('should load Kebutuhan Sistem tab', async ({ page }) => {
        await page.goto(process.env.BASE_URL + '/info_sistem');

        // Monitor AJAX errors
        const ajaxErrors = [];
        page.on('response', response => {
            if (response.url().includes('load_ekstensi') && response.status() >= 400) {
                ajaxErrors.push({
                    url: response.url(),
                    status: response.status()
                });
            }
        });

        // Click Kebutuhan Sistem tab
        console.log('🔍 Testing Kebutuhan Sistem tab...');
        await page.click('a[href="#ekstensi"]');

        // Tunggu tab content load
        await page.waitForTimeout(2000);

        // Cek tidak ada error
        if (ajaxErrors.length > 0) {
            console.log('❌ AJAX Errors:', ajaxErrors);
            throw new Error('Kebutuhan Sistem tab failed to load');
        }

        console.log('✅ Kebutuhan Sistem tab loaded');
    });

    test('should load Folder Desa tab', async ({ page }) => {
        await page.goto(process.env.BASE_URL + '/info_sistem');

        // Monitor AJAX errors
        const ajaxErrors = [];
        page.on('response', response => {
            if (response.url().includes('load_folder_desa') && response.status() >= 400) {
                ajaxErrors.push({
                    url: response.url(),
                    status: response.status()
                });
            }
        });

        // Click Folder Desa tab
        console.log('🔍 Testing Folder Desa tab...');
        await page.click('a[href="#folder_desa"]');

        // Tunggu tab content load
        await page.waitForTimeout(2000);

        // Cek tidak ada error
        if (ajaxErrors.length > 0) {
            console.log('❌ AJAX Errors:', ajaxErrors);
            throw new Error('Folder Desa tab failed to load');
        }

        console.log('✅ Folder Desa tab loaded');
    });
});
