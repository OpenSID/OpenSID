import { test, expect } from '@playwright/test';
import { setupAuth, STORAGE_STATE } from './auth.js';

/**
 * Test untuk mengecek DataTables di halaman Man User
 * Memastikan route POST berfungsi dan filter bekerja
 */
test.describe('Man User DataTables', () => {
    test.use({ storageState: STORAGE_STATE });

    test.beforeAll(async () => {
        await setupAuth();
    });

    test('should load user management table', async ({ page }) => {
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

        // Buka halaman man_user
        console.log('📍 Navigasi ke /man_user');
        await page.goto(process.env.BASE_URL + '/man_user');

        // Tunggu DataTables selesai load
        console.log('⏳ Menunggu DataTables load...');
        await page.waitForSelector('#tabeldata', { timeout: 10000 });

        // Tunggu processing selesai
        await page.waitForSelector('.dataTables_processing', { state: 'hidden', timeout: 10000 });

        // Cek tidak ada error alert
        const errorAlert = page.locator('.alert-danger');
        await expect(errorAlert).not.toBeVisible();

        // Cek DataTables ada data
        const tableRows = page.locator('#tabeldata tbody tr');
        await expect(tableRows.first()).toBeVisible();

        // Report errors jika ada
        if (consoleErrors.length > 0) {
            console.log('⚠️  Console Errors:', consoleErrors);
        }

        if (failedRequests.length > 0) {
            console.log('⚠️  Failed Requests:', failedRequests);
            throw new Error(`Found ${failedRequests.length} failed requests`);
        }

        console.log('✅ DataTables loaded successfully');
    });

    test('should filter by status', async ({ page }) => {
        await page.goto(process.env.BASE_URL + '/man_user');

        // Tunggu DataTables load
        await page.waitForSelector('#tabeldata', { timeout: 10000 });
        await page.waitForSelector('.dataTables_processing', { state: 'hidden', timeout: 10000 });

        // Ubah filter status
        console.log('🔍 Testing status filter...');
        await page.selectOption('#status', '0'); // Tidak Aktif

        // Tunggu DataTables reload
        await page.waitForSelector('.dataTables_processing', { state: 'visible', timeout: 5000 });
        await page.waitForSelector('.dataTables_processing', { state: 'hidden', timeout: 10000 });

        // Cek tidak ada error
        const errorAlert = page.locator('.alert-danger');
        await expect(errorAlert).not.toBeVisible();

        console.log('✅ Status filter working');
    });

    test('should filter by group', async ({ page }) => {
        await page.goto(process.env.BASE_URL + '/man_user');

        // Tunggu DataTables load
        await page.waitForSelector('#tabeldata', { timeout: 10000 });
        await page.waitForSelector('.dataTables_processing', { state: 'hidden', timeout: 10000 });

        // Cek apakah ada options di group filter
        const groupOptions = await page.locator('#group option').count();

        if (groupOptions > 1) {
            console.log('🔍 Testing group filter...');

            // Pilih group pertama (selain "Semua")
            await page.selectOption('#group', { index: 1 });

            // Tunggu DataTables reload
            await page.waitForSelector('.dataTables_processing', { state: 'visible', timeout: 5000 });
            await page.waitForSelector('.dataTables_processing', { state: 'hidden', timeout: 10000 });

            // Cek tidak ada error
            const errorAlert = page.locator('.alert-danger');
            await expect(errorAlert).not.toBeVisible();

            console.log('✅ Group filter working');
        } else {
            console.log('⏭️  Skipping group filter test (no groups available)');
        }
    });
});
