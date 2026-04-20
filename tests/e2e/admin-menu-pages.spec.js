import { test, expect } from '@playwright/test';
import { setupAuth, STORAGE_STATE } from './auth.js';
import { ALL_PAGES, DATATABLES_PAGES } from './helpers/menu-config.js';
import {
    setupPageMonitors,
    waitForPageReady,
    waitForDataTables,
    assertNoPageErrors,
    getPageUrl,
    checkForAlertDanger,
} from './helpers/page-helpers.js';

/**
 * Test Suite: Kesehatan Halaman Menu Admin
 *
 * Tujuan:
 *  1. Setiap halaman menu bisa dibuka tanpa error 404, 500, 403
 *  2. Setiap halaman tidak memiliki console error terkait DataTables
 *  3. Setiap halaman yang punya DataTables berhasil memuat data
 *  4. Tidak ada alert-danger yang muncul saat halaman dibuka
 */
test.describe('Admin Menu — Kesehatan Halaman (No 404/500/403, No DT Error)', () => {
    test.use({ storageState: STORAGE_STATE });

    test.beforeAll(async () => {
        await setupAuth();
    });

    // ─────────────────────────────────────────────────────────────────────
    // BAGIAN 2.1 — Semua halaman terbuka tanpa HTTP error
    // ─────────────────────────────────────────────────────────────────────
    test.describe('2.1 Tidak ada HTTP error (404 / 500)', () => {
        for (const menuPage of ALL_PAGES) {
            test(`[${menuPage.parent ?? 'Utama'}] ${menuPage.name}`, async ({ page }) => {
                const url = getPageUrl(menuPage.url);
                const monitors = setupPageMonitors(page);

                console.log(`\n📍 Navigasi ke: ${url}`);
                await page.goto(url, { timeout: 30000 });
                await waitForPageReady(page);

                // Pastikan tidak ada 404 / 500
                const httpErrors = monitors.httpErrors.filter(e => [404, 500].includes(e.status));
                if (httpErrors.length > 0) {
                    const details = httpErrors
                        .map(e => `[${e.status}] ${e.method} ${e.url}`)
                        .join('\n');
                    throw new Error(`HTTP error pada "${menuPage.name}":\n${details}`);
                }

                // Pastikan tidak ada alert-danger
                await checkForAlertDanger(page);

                // Pastikan halaman tidak redirect ke login (session masih valid)
                const currentUrl = page.url();
                expect(
                    currentUrl.includes('siteman'),
                    `Session expired atau redirect ke login pada halaman "${menuPage.name}"`
                ).toBeFalsy();

                console.log(`✅ ${menuPage.name} — OK (tidak ada HTTP error)`);
            });
        }
    });

    // ─────────────────────────────────────────────────────────────────────
    // BAGIAN 2.2 — Tidak ada error 403 Forbidden
    // ─────────────────────────────────────────────────────────────────────
    test.describe('2.2 Tidak ada error 403 Forbidden', () => {
        for (const menuPage of ALL_PAGES) {
            test(`[${menuPage.parent ?? 'Utama'}] ${menuPage.name}`, async ({ page }) => {
                const url = getPageUrl(menuPage.url);
                const monitors = setupPageMonitors(page);

                await page.goto(url, { timeout: 30000 });
                await waitForPageReady(page);

                // Cek HTTP 403
                const forbidden = monitors.httpErrors.filter(e => e.status === 403);
                if (forbidden.length > 0) {
                    const details = forbidden
                        .map(e => `[${e.status}] ${e.method} ${e.url}`)
                        .join('\n');
                    throw new Error(`403 Forbidden pada "${menuPage.name}":\n${details}`);
                }

                // Cek konten halaman tidak menunjukkan forbidden/unauthorized
                const bodyText = await page.locator('body').textContent();
                const isForbiddenPage = /403|Forbidden|Unauthorized|Tidak diizinkan/i.test(
                    bodyText ?? ''
                );

                expect(
                    isForbiddenPage,
                    `Halaman "${menuPage.name}" menampilkan pesan forbidden/unauthorized`
                ).toBeFalsy();

                console.log(`✅ ${menuPage.name} — OK (tidak ada 403)`);
            });
        }
    });

    // ─────────────────────────────────────────────────────────────────────
    // BAGIAN 2.3 — Tidak ada console error terkait DataTables
    // ─────────────────────────────────────────────────────────────────────
    test.describe('2.3 Tidak ada console error DataTables', () => {
        for (const menuPage of ALL_PAGES) {
            test(`[${menuPage.parent ?? 'Utama'}] ${menuPage.name}`, async ({ page }) => {
                const url = getPageUrl(menuPage.url);
                const monitors = setupPageMonitors(page);

                await page.goto(url, { timeout: 30000 });
                await waitForPageReady(page);

                // Validasi tidak ada DataTables error di console
                assertNoPageErrors(monitors, menuPage.name);

                console.log(`✅ ${menuPage.name} — OK (tidak ada DT console error)`);
            });
        }
    });

    // ─────────────────────────────────────────────────────────────────────
    // BAGIAN 2.4 — Halaman dengan DataTables berhasil memuat tabel
    // ─────────────────────────────────────────────────────────────────────
    test.describe('2.4 DataTables berhasil dimuat', () => {
        for (const menuPage of DATATABLES_PAGES) {
            test(`[${menuPage.parent ?? 'Utama'}] ${menuPage.name}`, async ({ page }) => {
                const url = getPageUrl(menuPage.url);
                const monitors = setupPageMonitors(page);

                console.log(`\n📍 Navigasi ke: ${url}`);
                await page.goto(url, { timeout: 30000 });

                // Tunggu tabel muncul
                await page.waitForSelector('#tabeldata', { timeout: 15000 });

                // Tunggu DataTables selesai processing
                await waitForDataTables(page);

                // Tabel harus visible
                await expect(page.locator('#tabeldata')).toBeVisible();

                // Tidak ada error di console terkait DataTables
                assertNoPageErrors(monitors, menuPage.name);

                // Tidak ada alert-danger
                await checkForAlertDanger(page);

                // Pastikan tbody ada (meski kosong, elemen harus ada)
                const tbody = page.locator('#tabeldata tbody');
                await expect(tbody).toBeAttached();

                console.log(`✅ ${menuPage.name} — DataTables OK`);
            });
        }
    });

    // ─────────────────────────────────────────────────────────────────────
    // BAGIAN 2.5 — DataTables AJAX request tidak menghasilkan error
    // ─────────────────────────────────────────────────────────────────────
    test.describe('2.5 AJAX request DataTables tidak error', () => {
        for (const menuPage of DATATABLES_PAGES) {
            test(`[${menuPage.parent ?? 'Utama'}] ${menuPage.name}`, async ({ page }) => {
                const url = getPageUrl(menuPage.url);
                const ajaxErrors = [];

                // Monitor response dari request AJAX DataTables (biasanya POST)
                page.on('response', async (response) => {
                    const reqUrl  = response.url();
                    const method  = response.request().method();
                    const status  = response.status();

                    // DataTables mengirim POST ke URL yang sama atau ke /datatables endpoint
                    const isDtRequest =
                        (method === 'POST' && reqUrl.includes(menuPage.url.split('/')[0])) ||
                        reqUrl.includes('datatables');

                    if (isDtRequest && status >= 400) {
                        ajaxErrors.push({ url: reqUrl, status, method });
                    }
                });

                await page.goto(url, { timeout: 30000 });
                await page.waitForSelector('#tabeldata', { timeout: 15000 });
                await waitForDataTables(page);

                if (ajaxErrors.length > 0) {
                    const details = ajaxErrors
                        .map(e => `[${e.status}] ${e.method} ${e.url}`)
                        .join('\n');
                    throw new Error(
                        `AJAX DataTables error pada "${menuPage.name}":\n${details}`
                    );
                }

                console.log(`✅ ${menuPage.name} — AJAX DataTables OK`);
            });
        }
    });

    // ─────────────────────────────────────────────────────────────────────
    // BAGIAN 2.6 — Laporan ringkasan (info test)
    // ─────────────────────────────────────────────────────────────────────
    test('2.6 Laporan ringkasan semua halaman (info)', async ({ page }) => {
        const results = [];

        for (const menuPage of ALL_PAGES) {
            const url = getPageUrl(menuPage.url);
            const monitors = setupPageMonitors(page);
            let status = 'OK';
            let note   = '';

            try {
                await page.goto(url, { timeout: 20000 });
                await waitForPageReady(page).catch(() => {});

                const httpErrors = monitors.httpErrors.filter(e => [404, 500, 403].includes(e.status));
                if (httpErrors.length > 0) {
                    status = 'ERROR';
                    note   = httpErrors.map(e => `HTTP ${e.status}`).join(', ');
                }
            } catch (err) {
                status = 'TIMEOUT';
                note   = err.message?.substring(0, 60) ?? '';
            }

            results.push({ name: menuPage.name, parent: menuPage.parent, url: menuPage.url, status, note });
        }

        console.log('');
        console.log('═══════════════════════════════════════════════════════════');
        console.log('📊 LAPORAN KESEHATAN SEMUA HALAMAN MENU ADMIN');
        console.log('═══════════════════════════════════════════════════════════');
        const ok      = results.filter(r => r.status === 'OK').length;
        const errors  = results.filter(r => r.status !== 'OK').length;
        console.log(`Total halaman  : ${results.length}`);
        console.log(`✅ OK          : ${ok}`);
        console.log(`❌ Error/Timeout: ${errors}`);
        console.log('');

        if (errors > 0) {
            console.log('Halaman dengan masalah:');
            results
                .filter(r => r.status !== 'OK')
                .forEach(r => console.log(`  ❌ [${r.status}] ${r.name} (${r.url}) — ${r.note}`));
        }

        console.log('═══════════════════════════════════════════════════════════');

        // Test ini informatif saja
        expect(results.length).toBeGreaterThan(0);
    });
});
