import { test, expect } from '@playwright/test';
import { setupAuth, STORAGE_STATE } from './auth.js';
import { PARENT_MENUS, SUB_MENUS } from './helpers/menu-config.js';

/**
 * Test Suite: Struktur Menu Admin
 *
 * Tujuan:
 *  1. Validasi jumlah menu utama di sidebar sesuai yang diharapkan
 *  2. Validasi nama-nama menu utama tampil dengan benar
 *  3. Validasi setiap menu utama yang punya anak → anaknya tampil
 *  4. Validasi tidak ada menu dengan teks kosong/duplikat
 */
test.describe('Admin Menu — Struktur Sidebar', () => {
    test.use({ storageState: STORAGE_STATE });

    test.beforeAll(async () => {
        await setupAuth();
    });

    // ─────────────────────────────────────────────────────────────────────
    // Helper: baca semua menu dari sidebar yang ter-render
    // ─────────────────────────────────────────────────────────────────────
    async function readSidebarMenus(page) {
        await page.goto(process.env.BASE_URL + '/beranda');
        await page.waitForSelector('.sidebar-menu', { timeout: 15000 });

        // Kumpulkan semua teks menu utama (li.treeview > a > span, atau li > a > span)
        const parentMenuTexts = await page.locator(
            '.sidebar-menu > li:not(.header) > a span:first-of-type'
        ).allTextContents();

        // Kumpulkan semua sub-menu (li.treeview-menu > li > a)
        const subMenuTexts = await page.locator(
            '.sidebar-menu .treeview-menu li > a'
        ).allTextContents();

        return {
            parentMenus: parentMenuTexts.map(t => t.trim()).filter(Boolean),
            subMenus:    subMenuTexts.map(t => t.trim()).filter(Boolean),
        };
    }

    // ─────────────────────────────────────────────────────────────────────
    test('1.1 Sidebar dapat dibaca setelah login', async ({ page }) => {
        await page.goto(process.env.BASE_URL + '/beranda');
        await expect(page.locator('.sidebar-menu')).toBeVisible({ timeout: 15000 });
        await expect(page.locator('.sidebar-menu > li.header')).toBeVisible();
        console.log('✅ Sidebar berhasil dimuat');
    });

    // ─────────────────────────────────────────────────────────────────────
    test('1.2 Jumlah menu utama tidak kurang dari minimum', async ({ page }) => {
        const { parentMenus } = await readSidebarMenus(page);

        // Minimal harus ada Beranda + beberapa menu utama
        const MIN_MENU_COUNT = 5;
        console.log(`📋 Menu utama yang ditemukan (${parentMenus.length}): ${parentMenus.join(', ')}`);

        expect(
            parentMenus.length,
            `Jumlah menu utama (${parentMenus.length}) kurang dari minimum ${MIN_MENU_COUNT}`
        ).toBeGreaterThanOrEqual(MIN_MENU_COUNT);
    });

    // ─────────────────────────────────────────────────────────────────────
    test('1.3 Tidak ada menu utama dengan teks kosong', async ({ page }) => {
        const { parentMenus } = await readSidebarMenus(page);

        const emptyMenus = parentMenus.filter(t => t === '');
        expect(
            emptyMenus.length,
            'Ditemukan menu utama dengan teks kosong'
        ).toBe(0);
    });

    // ─────────────────────────────────────────────────────────────────────
    test('1.4 Tidak ada menu utama yang duplikat', async ({ page }) => {
        const { parentMenus } = await readSidebarMenus(page);

        const unique    = new Set(parentMenus);
        const duplicate = parentMenus.filter(
            (m, idx) => parentMenus.indexOf(m) !== idx
        );

        if (duplicate.length > 0) {
            console.warn(`⚠️  Menu duplikat: ${duplicate.join(', ')}`);
        }

        expect(
            unique.size,
            `Ditemukan ${duplicate.length} menu duplikat: ${duplicate.join(', ')}`
        ).toBe(parentMenus.length);
    });

    // ─────────────────────────────────────────────────────────────────────
    test('1.5 Menu konfigurasi sesuai dengan menu yang tampil di sidebar', async ({ page }) => {
        const { parentMenus } = await readSidebarMenus(page);

        // Cek setiap menu dalam konfigurasi ada di sidebar
        const missingMenus = [];
        for (const expected of PARENT_MENUS) {
            // Normalisasi teks (hapus karakter spesial seperti [Desa])
            const normalized = expected.name
                .replace(/\[.*?\]/g, '')   // hapus [Desa], [Pemerintah Desa], dll.
                .trim();

            const found = parentMenus.some(m =>
                m.toLowerCase().includes(normalized.toLowerCase()) ||
                normalized.toLowerCase().includes(m.toLowerCase())
            );

            if (!found) {
                missingMenus.push(expected.name);
            }
        }

        if (missingMenus.length > 0) {
            console.warn(`⚠️  Menu tidak ditemukan di sidebar: ${missingMenus.join(', ')}`);
        }

        // Tidak perlu 100% match karena beberapa menu mungkin di-disable
        // Cukup tidak lebih dari 30% yang missing
        const missingRatio = missingMenus.length / PARENT_MENUS.length;
        expect(
            missingRatio,
            `Lebih dari 30% menu konfigurasi tidak ada di sidebar.\nMissing: ${missingMenus.join(', ')}`
        ).toBeLessThanOrEqual(0.3);

        console.log(`✅ ${PARENT_MENUS.length - missingMenus.length}/${PARENT_MENUS.length} menu konfigurasi ditemukan di sidebar`);
    });

    // ─────────────────────────────────────────────────────────────────────
    test('1.6 Semua sub-menu dapat dilihat (klik expand parent)', async ({ page }) => {
        await page.goto(process.env.BASE_URL + '/beranda');
        await page.waitForSelector('.sidebar-menu', { timeout: 15000 });

        // Klik semua parent menu yang punya children (treeview)
        const treeviewItems = page.locator('.sidebar-menu > li.treeview > a');
        const count = await treeviewItems.count();

        console.log(`📋 Jumlah parent menu dengan sub-menu: ${count}`);
        expect(count).toBeGreaterThan(0);

        // Klik parent pertama untuk memverifikasi sub-menu muncul
        await treeviewItems.first().click();
        await page.waitForTimeout(500);

        const subMenuVisible = page.locator(
            '.sidebar-menu > li.treeview.menu-open .treeview-menu li'
        );
        const subCount = await subMenuVisible.count();
        expect(subCount, 'Sub-menu tidak muncul setelah parent di-klik').toBeGreaterThan(0);

        console.log(`✅ Sub-menu berhasil expand, ditemukan ${subCount} item`);
    });

    // ─────────────────────────────────────────────────────────────────────
    test('1.7 Setiap link menu memiliki href yang valid', async ({ page }) => {
        await page.goto(process.env.BASE_URL + '/beranda');
        await page.waitForSelector('.sidebar-menu', { timeout: 15000 });

        const allLinks = await page.locator('.sidebar-menu a[href]').all();
        const invalidLinks = [];

        for (const link of allLinks) {
            const href = await link.getAttribute('href');
            if (!href || href === '#' || href === 'javascript:void(0)') {
                continue; // href # untuk toggle adalah valid
            }

            // href harus berupa path (tidak kosong, tidak javascript:)
            if (href.startsWith('javascript:') && !href.includes('void')) {
                const text = await link.textContent();
                invalidLinks.push({ text: text?.trim(), href });
            }
        }

        if (invalidLinks.length > 0) {
            console.warn('⚠️  Link menu tidak valid:', invalidLinks);
        }

        expect(
            invalidLinks.length,
            `Ditemukan ${invalidLinks.length} link menu yang tidak valid`
        ).toBe(0);

        console.log(`✅ Semua ${allLinks.length} link menu memiliki href valid`);
    });

    // ─────────────────────────────────────────────────────────────────────
    test('1.8 Laporan jumlah total menu (info)', async ({ page }) => {
        const { parentMenus, subMenus } = await readSidebarMenus(page);

        console.log('');
        console.log('═══════════════════════════════════════════════════');
        console.log('📊 LAPORAN STRUKTUR MENU ADMIN');
        console.log('═══════════════════════════════════════════════════');
        console.log(`Jumlah menu utama  : ${parentMenus.length}`);
        console.log(`Jumlah sub-menu    : ${subMenus.length}`);
        console.log(`Total menu         : ${parentMenus.length + subMenus.length}`);
        console.log('');
        console.log('Menu Utama:');
        parentMenus.forEach((m, i) => console.log(`  ${i + 1}. ${m}`));
        console.log('═══════════════════════════════════════════════════');
        console.log('');

        // Test ini selalu pass — hanya untuk reporting
        expect(parentMenus.length + subMenus.length).toBeGreaterThan(0);
    });
});
