/**
 * Helper utilities untuk E2E testing admin OpenSID
 *
 * Digunakan bersama oleh semua spec file admin-*.spec.js
 */

import path from 'path';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname  = path.dirname(__filename);

// ---------------------------------------------------------------------------
// Konstanta HTTP error yang dimonitor
// ---------------------------------------------------------------------------
const HTTP_ERROR_CODES = [403, 404, 500, 502, 503];

// Pola error console yang terkait DataTables
const DATATABLES_ERROR_PATTERNS = [
    /datatables/i,
    /datatable/i,
    /dt\.net/i,
    /cannot\s+read\s+propert/i,  // JS TypeError saat DataTables gagal
    /uncaught/i,
    /net::err/i,
];

// ---------------------------------------------------------------------------
// setupPageMonitors
// ---------------------------------------------------------------------------
/**
 * Pasang monitor console error dan failed request pada page.
 * Kembalikan objek { consoleErrors, failedRequests } yang terus diupdate
 * secara reaktif selama navigasi berlangsung.
 *
 * @param {import('@playwright/test').Page} page
 * @returns {{ consoleErrors: string[], failedRequests: object[], httpErrors: object[] }}
 */
export function setupPageMonitors(page) {
    const consoleErrors  = [];
    const failedRequests = [];
    const httpErrors     = [];

    page.on('console', (msg) => {
        if (msg.type() === 'error') {
            consoleErrors.push(msg.text());
        }
    });

    page.on('requestfailed', (request) => {
        failedRequests.push({
            url:     request.url(),
            method:  request.method(),
            failure: request.failure()?.errorText ?? 'unknown',
        });
    });

    page.on('response', (response) => {
        if (HTTP_ERROR_CODES.includes(response.status())) {
            // Abaikan asset statis yang optional (favicon, dll.)
            const url = response.url();
            if (!url.includes('favicon') && !url.includes('.ico')) {
                httpErrors.push({
                    url,
                    status: response.status(),
                    method: response.request().method(),
                });
            }
        }
    });

    return { consoleErrors, failedRequests, httpErrors };
}

// ---------------------------------------------------------------------------
// waitForPageReady
// ---------------------------------------------------------------------------
/**
 * Tunggu halaman benar-benar siap: tidak ada loading spinner,
 * tidak ada DataTables processing.
 *
 * @param {import('@playwright/test').Page} page
 * @param {number} timeout ms
 */
export async function waitForPageReady(page, timeout = 15000) {
    try {
        // Tunggu DOM ready
        await page.waitForLoadState('domcontentloaded', { timeout });

        // Jika ada DataTables, tunggu selesai processing
        const hasTable = await page.locator('#tabeldata').count() > 0;
        if (hasTable) {
            await waitForDataTables(page, timeout);
        }
    } catch {
        // Lanjutkan meski timeout — akan dicek di assertion
    }
}

// ---------------------------------------------------------------------------
// waitForDataTables
// ---------------------------------------------------------------------------
/**
 * Tunggu DataTables (#tabeldata) selesai memuat data.
 *
 * @param {import('@playwright/test').Page} page
 * @param {number} timeout ms
 */
export async function waitForDataTables(page, timeout = 15000) {
    // Tunggu tabel muncul
    await page.waitForSelector('#tabeldata', { timeout }).catch(() => {});

    // Tunggu processing indicator menghilang
    try {
        const processing = page.locator('.dataTables_processing');
        const count = await processing.count();
        if (count > 0) {
            // Tunggu visible dulu (bisa saja langsung hilang)
            await processing.waitFor({ state: 'hidden', timeout }).catch(() => {});
        }
    } catch {
        // Tidak ada processing indicator, lanjutkan
    }
}

// ---------------------------------------------------------------------------
// assertNoPageErrors
// ---------------------------------------------------------------------------
/**
 * Validasi bahwa tidak ada HTTP error (404/500/403) dan tidak ada console
 * error yang berkaitan dengan DataTables.
 *
 * @param {{ consoleErrors: string[], httpErrors: object[] }} monitors
 * @param {string} pageName Nama halaman untuk konteks error message
 * @param {{ strict?: boolean }} options
 *   strict=true  → gagal jika ada error apapun
 *   strict=false → hanya gagal jika ada DataTables error (default)
 */
export function assertNoPageErrors(monitors, pageName, options = {}) {
    const { strict = false } = options;

    // ── HTTP Errors ────────────────────────────────────────────────────────
    const criticalHttpErrors = monitors.httpErrors.filter(e => {
        // Skip 403 pada endpoint yang memang bisa diakses terbatas
        // (misal request AJAX ke route yang tidak ada izinnya)
        return [404, 500].includes(e.status);
    });

    if (criticalHttpErrors.length > 0) {
        const details = criticalHttpErrors
            .map(e => `  [${e.status}] ${e.method} ${e.url}`)
            .join('\n');
        throw new Error(`Halaman "${pageName}" memiliki HTTP error:\n${details}`);
    }

    // ── DataTables Console Errors ──────────────────────────────────────────
    const dtErrors = monitors.consoleErrors.filter(msg =>
        DATATABLES_ERROR_PATTERNS.some(pattern => pattern.test(msg))
    );

    if (dtErrors.length > 0) {
        const details = dtErrors.map(e => `  ${e}`).join('\n');
        throw new Error(`Halaman "${pageName}" memiliki DataTables console error:\n${details}`);
    }

    // ── Strict mode: semua console error ──────────────────────────────────
    if (strict && monitors.consoleErrors.length > 0) {
        const details = monitors.consoleErrors.map(e => `  ${e}`).join('\n');
        throw new Error(`Halaman "${pageName}" memiliki console error:\n${details}`);
    }
}

// ---------------------------------------------------------------------------
// getPageUrl
// ---------------------------------------------------------------------------
/**
 * Kembalikan URL lengkap dari BASE_URL + path menu.
 *
 * @param {string} menuUrl
 * @returns {string}
 */
export function getPageUrl(menuUrl) {
    const base = (process.env.BASE_URL || 'http://localhost/premium').replace(/\/$/, '');
    return `${base}/${menuUrl}`;
}

// ---------------------------------------------------------------------------
// checkForAlertDanger
// ---------------------------------------------------------------------------
/**
 * Pastikan tidak ada alert-danger yang terlihat di halaman.
 *
 * @param {import('@playwright/test').Page} page
 */
export async function checkForAlertDanger(page) {
    const alert = page.locator('.alert-danger');
    const count = await alert.count();
    if (count > 0) {
        const text = await alert.first().textContent();
        throw new Error(`Alert danger ditemukan: ${text?.trim()}`);
    }
}

// ---------------------------------------------------------------------------
// openAndCloseModal
// ---------------------------------------------------------------------------
/**
 * Klik sebuah tombol yang membuka modal, tunggu modal muncul,
 * lalu tutup modal dengan tombol close/X.
 *
 * @param {import('@playwright/test').Page} page
 * @param {import('@playwright/test').Locator} triggerLocator - Tombol yang membuka modal
 * @param {number} timeout
 * @returns {Promise<boolean>} true jika modal berhasil dibuka
 */
export async function openAndCloseModal(page, triggerLocator, timeout = 8000) {
    await triggerLocator.click();

    try {
        // Tunggu salah satu modal muncul
        const modal = page.locator('.modal.in, .modal[style*="display: block"]').first();
        await modal.waitFor({ state: 'visible', timeout });

        // Tutup modal
        const closeBtn = modal.locator('[data-dismiss="modal"], .close').first();
        const closeBtnCount = await closeBtn.count();
        if (closeBtnCount > 0) {
            await closeBtn.click();
            await modal.waitFor({ state: 'hidden', timeout: 5000 }).catch(() => {});
        }
        return true;
    } catch {
        return false;
    }
}

// ---------------------------------------------------------------------------
// logTestResult
// ---------------------------------------------------------------------------
/**
 * Print ringkasan hasil test ke console.
 *
 * @param {string} pageName
 * @param {{ passed: boolean, details?: string }} result
 */
export function logTestResult(pageName, result) {
    const icon = result.passed ? '✅' : '❌';
    console.log(`${icon} ${pageName}${result.details ? ' — ' + result.details : ''}`);
}
