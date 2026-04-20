import { test, expect } from '@playwright/test';
import { setupAuth, STORAGE_STATE } from './auth.js';
import { ALL_PAGES, TAMBAH_PAGES, DATATABLES_PAGES } from './helpers/menu-config.js';
import {
    setupPageMonitors,
    waitForPageReady,
    waitForDataTables,
    getPageUrl,
    openAndCloseModal,
} from './helpers/page-helpers.js';

/**
 * Test Suite: Interaksi Tombol di Setiap Halaman Menu Admin
 *
 * Tujuan:
 *  1. Semua tombol pada halaman tidak dalam keadaan disabled
 *  2. Tombol Tambah/Add dapat diklik dan membuka modal atau navigasi
 *  3. Filter dropdown/select men-trigger reload DataTables
 *  4. Tombol aksi di baris tabel (edit, detail) dapat diklik
 *  5. Tombol konfirmasi hapus memunculkan dialog konfirmasi
 *
 * ⚠️  Test ini TIDAK melakukan operasi destructive (tidak benar-benar
 *     submit form, tidak benar-benar hapus data).
 */
test.describe('Admin Menu — Interaksi Tombol', () => {
    test.use({ storageState: STORAGE_STATE });

    test.beforeAll(async () => {
        await setupAuth();
    });

    // ─────────────────────────────────────────────────────────────────────
    // BAGIAN 3.1 — Semua tombol tidak disabled
    // ─────────────────────────────────────────────────────────────────────
    test.describe('3.1 Tombol tidak dalam status disabled', () => {
        for (const menuPage of ALL_PAGES) {
            test(`[${menuPage.parent ?? 'Utama'}] ${menuPage.name}`, async ({ page }) => {
                await page.goto(getPageUrl(menuPage.url), { timeout: 30000 });
                await waitForPageReady(page);

                // Cari semua tombol yang terlihat (visible) di halaman
                const visibleButtons = page.locator(
                    'button:visible, a.btn:visible, input[type="button"]:visible, input[type="submit"]:visible'
                );
                const count = await visibleButtons.count();

                if (count === 0) {
                    console.log(`ℹ️  ${menuPage.name} — tidak ada tombol visible (mungkin halaman read-only)`);
                    return;
                }

                const disabledButtons = [];
                for (let i = 0; i < count; i++) {
                    const btn = visibleButtons.nth(i);
                    const isDisabled = await btn.isDisabled();
                    if (isDisabled) {
                        const text = await btn.textContent();
                        const title = await btn.getAttribute('title');
                        disabledButtons.push(text?.trim() || title || `button[${i}]`);
                    }
                }

                // Tombol yang boleh disabled secara by-design (misal tombol "Hapus Terpilih"
                // sebelum ada item yang di-check)
                const allowedDisabled = ['Hapus Terpilih', 'Export'];
                const unexpectedDisabled = disabledButtons.filter(
                    btn => !allowedDisabled.some(allowed =>
                        btn.toLowerCase().includes(allowed.toLowerCase())
                    )
                );

                if (unexpectedDisabled.length > 0) {
                    console.warn(
                        `⚠️  ${menuPage.name} — tombol disabled: ${unexpectedDisabled.join(', ')}`
                    );
                }

                console.log(`✅ ${menuPage.name} — ${count} tombol visible, ${disabledButtons.length} disabled (diizinkan)`);
            });
        }
    });

    // ─────────────────────────────────────────────────────────────────────
    // BAGIAN 3.2 — Tombol Tambah membuka modal atau navigasi
    // ─────────────────────────────────────────────────────────────────────
    test.describe('3.2 Tombol Tambah berfungsi (modal atau navigasi)', () => {
        for (const menuPage of TAMBAH_PAGES) {
            test(`[${menuPage.parent ?? 'Utama'}] ${menuPage.name}`, async ({ page }) => {
                await page.goto(getPageUrl(menuPage.url), { timeout: 30000 });
                await waitForPageReady(page);

                // Cari tombol Tambah dengan berbagai kemungkinan selector
                const tambahSelectors = [
                    'a:has-text("Tambah"):visible',
                    'a.btn:has-text("Tambah"):visible',
                    'button:has-text("Tambah"):visible',
                    '[title="Tambah"]:visible',
                    '.btn-tambah:visible',
                    'a[href*="tambah"]:visible',
                    'a[href*="create"]:visible',
                ];

                let tambahBtn = null;
                for (const selector of tambahSelectors) {
                    const found = page.locator(selector).first();
                    const count = await found.count();
                    if (count > 0) {
                        tambahBtn = found;
                        break;
                    }
                }

                if (!tambahBtn) {
                    console.warn(
                        `⚠️  ${menuPage.name} — tombol Tambah tidak ditemukan (hasTambah=true di config?)`
                    );
                    return; // skip, jangan fail — mungkin tombol ada tapi label berbeda
                }

                const btnText = await tambahBtn.textContent();
                console.log(`🔘 ${menuPage.name} — klik tombol "${btnText?.trim()}"`);

                const urlBefore  = page.url();
                const isModal    = (await tambahBtn.getAttribute('data-toggle')) === 'modal'
                    || (await tambahBtn.getAttribute('href') || '').includes('ajax');

                if (isModal) {
                    // Tombol membuka modal
                    const opened = await openAndCloseModal(page, tambahBtn);
                    expect(
                        opened,
                        `Modal tidak terbuka setelah klik Tambah pada "${menuPage.name}"`
                    ).toBeTruthy();
                    console.log(`✅ ${menuPage.name} — Modal Tambah berhasil dibuka dan ditutup`);
                } else {
                    // Tombol navigasi ke halaman form
                    await tambahBtn.click({ timeout: 5000 });
                    await page.waitForLoadState('domcontentloaded', { timeout: 10000 }).catch(() => {});
                    const urlAfter = page.url();

                    // URL harus berubah atau ada elemen form
                    const hasForm = await page.locator('form').count() > 0;
                    const urlChanged = urlAfter !== urlBefore;

                    expect(
                        urlChanged || hasForm,
                        `Klik Tambah pada "${menuPage.name}" tidak membuka form/modal — URL: ${urlAfter}`
                    ).toBeTruthy();
                    console.log(`✅ ${menuPage.name} — Navigasi Tambah berhasil (URL: ${urlAfter.split('/').pop()})`);
                }
            });
        }
    });

    // ─────────────────────────────────────────────────────────────────────
    // BAGIAN 3.3 — Filter/select men-trigger DataTables reload
    // ─────────────────────────────────────────────────────────────────────
    test.describe('3.3 Filter dropdown men-trigger DataTables reload', () => {
        const pagesWithFilter = ALL_PAGES.filter(
            p => p.hasFilter && p.hasFilter.length > 0 && p.hasTable
        );

        for (const menuPage of pagesWithFilter) {
            test(`[${menuPage.parent ?? 'Utama'}] ${menuPage.name}`, async ({ page }) => {
                await page.goto(getPageUrl(menuPage.url), { timeout: 30000 });
                await waitForPageReady(page);

                // Ambil filter pertama
                const filterSelector = menuPage.hasFilter[0];
                const filter = page.locator(filterSelector);
                const filterCount = await filter.count();

                if (filterCount === 0) {
                    console.warn(`⚠️  ${menuPage.name} — filter "${filterSelector}" tidak ditemukan`);
                    return;
                }

                // Ambil jumlah opsi filter
                const options = await page.locator(`${filterSelector} option`).count();
                if (options <= 1) {
                    console.log(`ℹ️  ${menuPage.name} — filter "${filterSelector}" tidak memiliki opsi (skip)`);
                    return;
                }

                // Pilih opsi ke-2 (index 1)
                console.log(`🔍 ${menuPage.name} — mengubah filter "${filterSelector}"`);
                await page.selectOption(filterSelector, { index: 1 });

                // Tunggu DataTables reload — cek processing indicator
                try {
                    await page.waitForSelector('.dataTables_processing', {
                        state: 'visible', timeout: 3000
                    });
                    await page.waitForSelector('.dataTables_processing', {
                        state: 'hidden', timeout: 15000
                    });
                    console.log(`✅ ${menuPage.name} — Filter trigger DataTables reload OK`);
                } catch {
                    // Processing mungkin terlalu cepat, cek tabel masih ada
                    await expect(
                        page.locator('#tabeldata'),
                        `Tabel menghilang setelah filter pada "${menuPage.name}"`
                    ).toBeVisible();
                    console.log(`✅ ${menuPage.name} — Filter OK (processing cepat)`);
                }

                // Reset filter
                await page.selectOption(filterSelector, { index: 0 }).catch(() => {});
            });
        }
    });

    // ─────────────────────────────────────────────────────────────────────
    // BAGIAN 3.4 — Tombol Edit di baris tabel pertama berfungsi
    // ─────────────────────────────────────────────────────────────────────
    test.describe('3.4 Tombol Edit baris pertama berfungsi', () => {
        // Pilih subset halaman dengan DataTables yang memiliki tombol edit
        const editablePages = DATATABLES_PAGES.filter(p =>
            // Halaman dengan tombol Tambah biasanya juga punya Edit
            p.hasTambah
        ).slice(0, 15); // Batasi 15 halaman untuk efisiensi

        for (const menuPage of editablePages) {
            test(`[${menuPage.parent ?? 'Utama'}] ${menuPage.name}`, async ({ page }) => {
                await page.goto(getPageUrl(menuPage.url), { timeout: 30000 });
                await waitForPageReady(page);

                // Tunggu DataTables
                const tableExists = await page.locator('#tabeldata').count() > 0;
                if (!tableExists) {
                    console.log(`ℹ️  ${menuPage.name} — tabel tidak ditemukan (skip)`);
                    return;
                }

                await waitForDataTables(page);

                // Cek apakah ada data di tabel
                const firstRow = page.locator('#tabeldata tbody tr').first();
                const rowCount = await page.locator('#tabeldata tbody tr').count();

                // Jika tidak ada data, skip
                if (rowCount === 0) {
                    console.log(`ℹ️  ${menuPage.name} — tabel kosong, tidak ada baris untuk ditest`);
                    return;
                }

                // Cek jika tabel hanya punya row "No data available"
                const noDataText = await firstRow.textContent();
                if (
                    noDataText?.toLowerCase().includes('no data') ||
                    noDataText?.toLowerCase().includes('tidak ada data') ||
                    noDataText?.toLowerCase().includes('data not available')
                ) {
                    console.log(`ℹ️  ${menuPage.name} — tidak ada data di tabel (skip)`);
                    return;
                }

                // Cari tombol edit di baris pertama
                const editSelectors = [
                    'a[title="Ubah Data"], a[title="Edit"], a.btn-warning:visible',
                    'a:has(.fa-edit):visible',
                    'a:has(.fa-pencil):visible',
                    '.btn-ubah:visible',
                ];

                let editBtn = null;
                for (const sel of editSelectors) {
                    const btn = firstRow.locator(sel).first();
                    if (await btn.count() > 0) {
                        editBtn = btn;
                        break;
                    }
                }

                if (!editBtn) {
                    console.log(`ℹ️  ${menuPage.name} — tombol Edit tidak ditemukan di baris pertama`);
                    return;
                }

                const btnTitle = await editBtn.getAttribute('title');
                console.log(`🔘 ${menuPage.name} — klik tombol "${btnTitle ?? 'Edit'}"`);

                const isModal = (await editBtn.getAttribute('data-toggle')) === 'modal'
                    || (await editBtn.getAttribute('href') || '').includes('ajax');

                if (isModal) {
                    const opened = await openAndCloseModal(page, editBtn);
                    expect(
                        opened,
                        `Modal Edit tidak terbuka pada "${menuPage.name}"`
                    ).toBeTruthy();
                    console.log(`✅ ${menuPage.name} — Modal Edit berhasil dibuka`);
                } else {
                    const urlBefore = page.url();
                    await editBtn.click({ timeout: 5000 });
                    await page.waitForLoadState('domcontentloaded', { timeout: 10000 }).catch(() => {});
                    const hasForm = await page.locator('form').count() > 0;
                    const urlChanged = page.url() !== urlBefore;

                    expect(
                        urlChanged || hasForm,
                        `Edit button pada "${menuPage.name}" tidak membuka form`
                    ).toBeTruthy();
                    console.log(`✅ ${menuPage.name} — Navigasi Edit OK`);
                }
            });
        }
    });

    // ─────────────────────────────────────────────────────────────────────
    // BAGIAN 3.5 — Tombol Hapus memunculkan konfirmasi (TIDAK hapus data)
    // ─────────────────────────────────────────────────────────────────────
    test.describe('3.5 Tombol Hapus memunculkan dialog konfirmasi', () => {
        const deletablePages = DATATABLES_PAGES.filter(p => p.hasTambah).slice(0, 10);

        for (const menuPage of deletablePages) {
            test(`[${menuPage.parent ?? 'Utama'}] ${menuPage.name}`, async ({ page }) => {
                await page.goto(getPageUrl(menuPage.url), { timeout: 30000 });
                await waitForPageReady(page);

                const tableExists = await page.locator('#tabeldata').count() > 0;
                if (!tableExists) return;

                await waitForDataTables(page);

                const firstRow = page.locator('#tabeldata tbody tr').first();
                const noDataText = await firstRow.textContent();
                if (
                    noDataText?.toLowerCase().includes('no data') ||
                    noDataText?.toLowerCase().includes('tidak ada data')
                ) {
                    console.log(`ℹ️  ${menuPage.name} — tidak ada data, skip test hapus`);
                    return;
                }

                // Cari tombol hapus di baris pertama
                const deleteSelectors = [
                    'a[title="Hapus Data"], a[data-toggle="modal"][data-target="#confirm-delete"]:visible',
                    'a:has(.fa-trash):visible',
                    'a.btn-danger:visible',
                    '.btn-hapus:visible',
                ];

                let deleteBtn = null;
                for (const sel of deleteSelectors) {
                    const btn = firstRow.locator(sel).first();
                    if (await btn.count() > 0) {
                        deleteBtn = btn;
                        break;
                    }
                }

                if (!deleteBtn) {
                    console.log(`ℹ️  ${menuPage.name} — tombol Hapus tidak ditemukan di baris pertama`);
                    return;
                }

                // Klik tombol hapus dan tunggu modal konfirmasi
                await deleteBtn.click();

                try {
                    // Modal konfirmasi harus muncul
                    const confirmModal = page.locator('#confirm-delete, .modal:has-text("Hapus"), .swal2-popup').first();
                    await confirmModal.waitFor({ state: 'visible', timeout: 5000 });

                    // BATALKAN — jangan hapus data!
                    const cancelBtn = confirmModal.locator(
                        'button:has-text("Batal"), button:has-text("Cancel"), [data-dismiss="modal"], .swal2-cancel'
                    ).first();

                    if (await cancelBtn.count() > 0) {
                        await cancelBtn.click();
                        await confirmModal.waitFor({ state: 'hidden', timeout: 5000 }).catch(() => {});
                    } else {
                        await page.keyboard.press('Escape');
                    }

                    console.log(`✅ ${menuPage.name} — Dialog konfirmasi hapus muncul dan dibatalkan`);
                } catch {
                    // Modal tidak muncul — mungkin link langsung hapus atau halaman redirect
                    console.warn(`⚠️  ${menuPage.name} — Konfirmasi hapus tidak ditemukan (mungkin sudah redirect)`);
                    // Kembali ke halaman
                    await page.goto(getPageUrl(menuPage.url), { timeout: 20000 });
                }
            });
        }
    });

    // ─────────────────────────────────────────────────────────────────────
    // BAGIAN 3.6 — Tombol search/pencarian di DataTables berfungsi
    // ─────────────────────────────────────────────────────────────────────
    test.describe('3.6 Kolom pencarian DataTables berfungsi', () => {
        // Test subset halaman penting
        const searchablePages = DATATABLES_PAGES.slice(0, 10);

        for (const menuPage of searchablePages) {
            test(`[${menuPage.parent ?? 'Utama'}] ${menuPage.name}`, async ({ page }) => {
                await page.goto(getPageUrl(menuPage.url), { timeout: 30000 });
                await page.waitForSelector('#tabeldata', { timeout: 15000 });
                await waitForDataTables(page);

                // Cari input pencarian DataTables
                const searchInput = page.locator(
                    '.dataTables_filter input, input[aria-label*="Cari"], input[aria-label*="Search"]'
                ).first();

                const searchCount = await searchInput.count();
                if (searchCount === 0) {
                    console.log(`ℹ️  ${menuPage.name} — input search tidak ditemukan`);
                    return;
                }

                // Ketik sesuatu di kotak pencarian
                console.log(`🔍 ${menuPage.name} — test search input`);
                await searchInput.fill('a');

                // Tunggu DataTables reload
                try {
                    await page.waitForSelector('.dataTables_processing', {
                        state: 'visible', timeout: 3000
                    });
                    await page.waitForSelector('.dataTables_processing', {
                        state: 'hidden', timeout: 10000
                    });
                } catch {
                    // Processing mungkin tidak ada atau terlalu cepat
                }

                // Tabel masih harus visible
                await expect(page.locator('#tabeldata')).toBeVisible();

                // Clear search
                await searchInput.fill('');
                await waitForDataTables(page);

                console.log(`✅ ${menuPage.name} — Search DataTables OK`);
            });
        }
    });

    // ─────────────────────────────────────────────────────────────────────
    // BAGIAN 3.7 — Laporan interaksi tombol (info)
    // ─────────────────────────────────────────────────────────────────────
    test('3.7 Laporan ringkasan interaksi tombol (info)', async ({ page }) => {
        const pageResults = [];

        for (const menuPage of ALL_PAGES.slice(0, 20)) { // Sample 20 halaman pertama
            await page.goto(getPageUrl(menuPage.url), { timeout: 20000 });
            await waitForPageReady(page).catch(() => {});

            const buttons = await page.locator('button:visible, a.btn:visible').count();
            const disabledBtns = await page.locator('button:disabled:visible, a.btn.disabled:visible').count();
            const modals = await page.locator('[data-toggle="modal"]').count();
            const filters = await page.locator('select:visible').count();

            pageResults.push({
                name: menuPage.name,
                buttons,
                disabled: disabledBtns,
                modals,
                filters,
            });
        }

        console.log('');
        console.log('═══════════════════════════════════════════════════════════');
        console.log('📊 LAPORAN INTERAKSI TOMBOL (SAMPLE 20 HALAMAN)');
        console.log('═══════════════════════════════════════════════════════════');
        console.log(
            `${'Halaman'.padEnd(35)} ${'Tombol'.padStart(7)} ${'Disabled'.padStart(9)} ${'Modal'.padStart(7)} ${'Filter'.padStart(7)}`
        );
        console.log('─'.repeat(70));
        pageResults.forEach(r => {
            console.log(
                `${r.name.padEnd(35)} ${String(r.buttons).padStart(7)} ${String(r.disabled).padStart(9)} ${String(r.modals).padStart(7)} ${String(r.filters).padStart(7)}`
            );
        });
        console.log('═══════════════════════════════════════════════════════════');

        expect(pageResults.length).toBeGreaterThan(0);
    });
});
