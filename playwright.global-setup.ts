import { setupAuth, checkLoginStatus, isSessionValid } from './tests/e2e/auth.js';
import { chromium } from '@playwright/test';
import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

async function globalSetup() {
    console.log('\n╔══════════════════════════════════════════════════════╗');
    console.log('║  🚀 PLAYWRIGHT GLOBAL SETUP                          ║');
    console.log('║  Check login status dan setup authentication         ║');
    console.log('╚══════════════════════════════════════════════════════╝\n');

    try {
        // Step 1: Cek apakah session sudah ada dan valid
        console.log('📋 Step 1: Cek apakah session sudah valid...\n');
        const sessionValid = await isSessionValid();

        if (sessionValid) {
            console.log('✅ Session masih valid, menggunakan session yang tersimpan\n');
            console.log('✅ GLOBAL SETUP SELESAI - Login tidak diperlukan\n');
            return;
        }

        // Step 2: Jika session tidak valid, cek apakah sudah login di server
        console.log('\n📋 Step 2: Cek status login di server...\n');
        const headless = process.env.HEADLESS === 'true';
        const browser = await chromium.launch({ headless });
        const context = await browser.newContext();

        try {
            const isAlreadyLoggedIn = await checkLoginStatus(context);

            if (isAlreadyLoggedIn) {
                console.log('\n✅ Sudah login! (Redirect ke /beranda)\n');
                console.log('💾 Menyimpan session ke auth.json...');
                const STORAGE_STATE = path.join(__dirname, 'tests/e2e/storage/auth.json');
                const storageDir = path.dirname(STORAGE_STATE);
                if (!fs.existsSync(storageDir)) {
                    fs.mkdirSync(storageDir, { recursive: true });
                }
                await context.storageState({ path: STORAGE_STATE });
                console.log(`✅ Session disimpan: ${STORAGE_STATE}\n`);
                console.log('✅ GLOBAL SETUP SELESAI\n');
                return;
            }

        } finally {
            await browser.close();
        }

        // Step 3: Jika belum login, jalankan setup auth
        console.log('\n📋 Step 3: Belum login, melakukan proses login...\n');
        await setupAuth();

        console.log('\n✅ GLOBAL SETUP SELESAI - Login berhasil\n');

    } catch (error) {
        console.error('\n╔════════════════════════════════════════╗');
        console.error('║  ❌ GLOBAL SETUP GAGAL                 ║');
        console.error('╚════════════════════════════════════════╝\n');
        console.error(`Error: ${error.message}\n`);
        throw error;
    }
}

export default globalSetup;
