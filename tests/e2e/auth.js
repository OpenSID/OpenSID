import { chromium } from '@playwright/test';
import fs from 'fs';
import path from 'path';
import dotenv from 'dotenv';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

// Load environment variables from tests/e2e/.env
dotenv.config({ path: path.join(__dirname, '.env') });

const STORAGE_STATE = path.join(__dirname, 'storage', 'auth.json');

/**
 * Cek apakah sudah login dengan cara akses /siteman
 * Jika redirect ke /beranda berarti sudah login
 * Jika tetap di /siteman berarti session habis atau belum login
 *
 * @param {import('@playwright/test').BrowserContext} context - Browser context dengan session
 * @returns {Promise<boolean>} - true jika sudah login, false jika belum
 */
async function checkLoginStatus(context) {
    const page = await context.newPage();

    try {
        const baseURL = process.env.BASE_URL || 'http://localhost/premium';
        const sitemanURL = `${baseURL}/siteman`;

        console.log('🔍 Mengecek status login...');
        console.log(`📍 Akses: ${sitemanURL}`);

        // Akses /siteman
        await page.goto(sitemanURL, { waitUntil: 'networkidle', timeout: 10000 });

        // Tunggu sebentar untuk redirect
        await page.waitForTimeout(1000);

        const currentURL = page.url();
        console.log(`📍 URL saat ini: ${currentURL}`);

        // Jika di-redirect ke /beranda berarti sudah login
        if (currentURL.includes('/beranda')) {
            console.log('✅ Sudah login (redirect ke /beranda)');
            return true;
        }

        // Jika masih di /siteman berarti belum login atau session habis
        if (currentURL.includes('/siteman')) {
            console.log('❌ Belum login atau session habis (masih di /siteman)');
            return false;
        }

        // Fallback: cek elemen dashboard
        const hasDashboard = await page.locator('aside.main-sidebar, .main-sidebar').count() > 0;
        if (hasDashboard) {
            console.log('✅ Sudah login (ditemukan elemen dashboard)');
            return true;
        }

        console.log('❌ Status login tidak terdeteksi');
        return false;

    } catch (error) {
        console.log(`⚠️  Error saat cek status: ${error.message}`);
        return false;
    } finally {
        await page.close();
    }
}

/**
 * Fungsi untuk melakukan login dan menyimpan session
 * @param {string} username - Username untuk login
 * @param {string} password - Password untuk login
 * @returns {Promise<void>}
 */
async function login(username, password) {
    console.log('🔐 Memulai proses login...');
    console.log(`📍 Target: ${process.env.BASE_URL}/siteman`);

    const headless = process.env.HEADLESS === 'true';
    const browser = await chromium.launch({ headless });
    const context = await browser.newContext({
        viewport: { width: 1280, height: 720 },
    });
    const page = await context.newPage();

    try {
        // Navigasi ke halaman login OpenSID
        const baseURL = process.env.BASE_URL || 'http://localhost/premium';
        const loginURL = `${baseURL}/siteman`;

        console.log(`🌐 Membuka halaman login: ${loginURL}`);
        await page.goto(loginURL, { waitUntil: 'networkidle' });

        // Tunggu sebentar untuk redirect otomatis (jika sudah login)
        await page.waitForTimeout(1000);

        const currentURL = page.url();

        // Cek apakah sudah di-redirect ke /beranda (sudah login)
        if (currentURL.includes('/beranda')) {
            console.log('✅ Sudah login sebelumnya! (auto redirect ke /beranda)');

            // Simpan session
            const storageDir = path.dirname(STORAGE_STATE);
            if (!fs.existsSync(storageDir)) {
                fs.mkdirSync(storageDir, { recursive: true });
            }
            await context.storageState({ path: STORAGE_STATE });
            console.log(`💾 Session disimpan di ${STORAGE_STATE}`);

            await page.screenshot({ path: path.join(__dirname, 'storage', 'already-logged-in.png') });
            return;
        }

        // Jika masih di /siteman, lakukan login
        console.log('📝 Melakukan login...');

        // Tunggu form login muncul
        await page.waitForSelector('input[name="username"], input#username, input[type="text"]', { timeout: 10000 });

        // Isi form login - coba berbagai selector yang mungkin
        console.log(`👤 Mengisi username: ${username}`);
        const usernameSelector = await page.locator('input[name="username"], input#username').first();
        await usernameSelector.fill(username);

        console.log(`🔑 Mengisi password`);
        const passwordSelector = await page.locator('input[name="password"], input#password, input[type="password"]').first();
        await passwordSelector.fill(password);

        // Isi CAPTCHA jika ada
        const captchaCode = process.env.LOGIN_CAPTCHA || '9ZQTHC';
        const captchaField = await page.locator('input[name="captcha_code"]').count();
        if (captchaField > 0) {
            await page.evaluate(() => {
                const el = document.querySelector('[name="captcha_code"]');
                if (el) {
                    el.setAttribute('name', 'secret_code');
                    el.setAttribute('maxlength', '10');
                }
            });
            console.log(`🔐 Mengisi CAPTCHA: ${captchaCode}`);
            await page.fill('input[name="secret_code"]', captchaCode);
        }

        // Screenshot sebelum login (untuk debugging)
        await page.screenshot({ path: path.join(__dirname, 'storage', 'before-login.png') });
        console.log('📸 Screenshot sebelum login disimpan');

        // Click tombol login
        console.log('🖱️  Klik tombol login...');
        const loginButton = await page.locator('button[type="submit"], input[type="submit"], button:has-text("Login"), button:has-text("Masuk")').first();
        await loginButton.click();

        // Tunggu redirect atau perubahan halaman
        await page.waitForLoadState('networkidle', { timeout: 15000 });
        await page.waitForTimeout(1000);

        // Cek hasil login
        const afterLoginURL = page.url();
        console.log(`📍 URL setelah login: ${afterLoginURL}`);

        // Jika di-redirect ke /beranda berarti login berhasil
        if (afterLoginURL.includes('/beranda')) {
            console.log('✅ Login berhasil! (redirect ke /beranda)');
        }
        // Jika masih di /siteman berarti login gagal
        else if (afterLoginURL.includes('/siteman')) {
            // Cek apakah ada error message
            const errorMsg = await page.locator('.alert-danger, .error-message, .login-error').first().textContent().catch(() => null);
            if (errorMsg) {
                throw new Error(`Login gagal: ${errorMsg}`);
            }

            // Screenshot untuk debugging
            await page.screenshot({ path: path.join(__dirname, 'storage', 'login-failed.png') });
            throw new Error('Login gagal - masih di halaman /siteman. Cek screenshot: tests/e2e/storage/login-failed.png');
        }
        // Fallback: cek elemen dashboard
        else {
            const hasDashboard = await page.locator('aside.main-sidebar, .main-sidebar').count() > 0;
            if (!hasDashboard) {
                throw new Error('Login gagal - tidak ditemukan elemen dashboard');
            }
            console.log('✅ Login berhasil! (ditemukan elemen dashboard)');
        }

        // Screenshot setelah login berhasil
        await page.screenshot({ path: path.join(__dirname, 'storage', 'after-login.png') });
        console.log('📸 Screenshot setelah login disimpan');

        // Simpan storage state untuk reuse
        const storageDir = path.dirname(STORAGE_STATE);
        if (!fs.existsSync(storageDir)) {
            fs.mkdirSync(storageDir, { recursive: true });
        }

        await context.storageState({ path: STORAGE_STATE });
        console.log(`💾 Session disimpan di ${STORAGE_STATE}`);

    } catch (error) {
        console.error('❌ Login gagal:', error.message);

        // Screenshot untuk debugging
        await page.screenshot({ path: path.join(__dirname, 'storage', 'error-screenshot.png') }).catch(() => { });
        console.log('📸 Screenshot error disimpan di tests/e2e/storage/error-screenshot.png');

        throw error;
    } finally {
        await browser.close();
    }
}

/**
 * Cek apakah session masih valid dengan cara akses /siteman
 * Jika redirect ke /beranda berarti session valid
 * Jika tetap di /siteman berarti session habis
 *
 * @returns {Promise<boolean>}
 */
async function isSessionValid() {
    if (!fs.existsSync(STORAGE_STATE)) {
        console.log('⚠️  File auth.json tidak ditemukan');
        return false;
    }

    // Launch browser dengan session tersimpan
    const headless = process.env.HEADLESS === 'true';
    const browser = await chromium.launch({ headless });
    const context = await browser.newContext({
        storageState: STORAGE_STATE,
    });

    try {
        // Cek status login
        const isLoggedIn = await checkLoginStatus(context);
        await browser.close();
        return isLoggedIn;
    } catch (error) {
        console.log(`⚠️  Error saat cek session: ${error.message}`);
        await browser.close();
        return false;
    }
}

/**
 * Setup authentication untuk test
 * Digunakan di global setup atau sebelum test
 */
async function setupAuth() {
    console.log('🔥 OpenSID E2E Testing - Authentication Setup');
    console.log('==============================================\n');

    // Cek session
    const sessionValid = await isSessionValid();

    if (!sessionValid) {
        console.log('⚠️  Session tidak valid atau tidak ditemukan\n');

        // Ambil credentials dari environment variables
        const username = process.env.LOGIN_USERNAME || 'admin';
        const password = process.env.LOGIN_PASSWORD || 'sid304';

        console.log(`👤 Username: ${username}`);
        console.log(`🔑 Password: ${'*'.repeat(password.length)}\n`);

        await login(username, password);
    } else {
        console.log('✅ Session masih valid, menggunakan session yang tersimpan\n');
    }

    console.log('🚀 Authentication setup selesai\n');
}

export { login, isSessionValid, checkLoginStatus, setupAuth, STORAGE_STATE };
