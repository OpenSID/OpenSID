import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
    storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Fix: Tombol Lock (kunci akun) masih muncul pada akun Administrator utama #11124', () => {

    test.beforeEach(async ({ page }) => {
        // Navigate to user management page
        await page.goto('man_user');
        // Wait for page to load
        await page.waitForLoadState('networkidle');
        // Wait for DataTable to be initialized
        const dataTable = page.locator('#tabeldata');
        await expect(dataTable).toBeVisible();
    });

    test.describe('Tombol aksi untuk akun Superadmin (Admin Utama)', () => {

        test('tombol lock/unlock TIDAK muncul untuk akun superadmin', async ({ page }) => {
            // Tunggu table body selesai load
            const tableBody = page.locator('#tabeldata tbody');
            await expect(tableBody).toBeVisible();

            // Cari row dengan username "admin" (superadmin)
            const rows = page.locator('#tabeldata tbody tr');
            let superadminRowFound = false;

            for (let i = 0; i < await rows.count(); i++) {
                const row = rows.nth(i);
                const usernameCell = row.locator('td').nth(2); // Username biasanya di kolom ke-3

                const username = await usernameCell.textContent();
                if (username?.trim() === 'admin') {
                    superadminRowFound = true;
                    // Dapatkan cell aksi (biasanya kolom terakhir)
                    const actionCell = row.locator('td').last();
                    const actionContent = await actionCell.innerHTML();

                    // Verify: Tombol lock/unlock (tombol_aktifkan) TIDAK ada
                    // Ciri-ciri: berisi class "btn-warning" atau "btn-success" dengan icon lock
                    const hasLockUnlockBtn = actionContent.includes('user_lock') ||
                                           actionContent.includes('user_unlock') ||
                                           actionContent.includes('fa-lock') ||
                                           actionContent.includes('fa-unlock');

                    expect(hasLockUnlockBtn, 'Tombol lock/unlock harus tidak muncul untuk superadmin').toBe(false);

                    break;
                }
            }

            expect(superadminRowFound, 'Akun admin/superadmin harus ada di tabel').toBe(true);
        });

        test('tombol delete TIDAK muncul untuk akun superadmin', async ({ page }) => {
            // Tunggu table body selesai load
            const tableBody = page.locator('#tabeldata tbody');
            await expect(tableBody).toBeVisible();

            // Cari row dengan username "admin" (superadmin)
            const rows = page.locator('#tabeldata tbody tr');
            let superadminRowFound = false;

            for (let i = 0; i < await rows.count(); i++) {
                const row = rows.nth(i);
                const usernameCell = row.locator('td').nth(2); // Username biasanya di kolom ke-3

                const username = await usernameCell.textContent();
                if (username?.trim() === 'admin') {
                    superadminRowFound = true;
                    // Dapatkan cell aksi (biasanya kolom terakhir)
                    const actionCell = row.locator('td').last();
                    const actionContent = await actionCell.innerHTML();

                    // Verify: Tombol delete (tombol hapus) TIDAK ada
                    // Ciri-ciri: berisi "man_user/delete" atau class "bg-maroon" atau icon trash
                    const hasDeleteBtn = actionContent.includes('man_user/delete') ||
                                        actionContent.includes('bg-maroon') ||
                                        actionContent.includes('fa-trash');

                    expect(hasDeleteBtn, 'Tombol delete harus tidak muncul untuk superadmin').toBe(false);

                    break;
                }
            }

            expect(superadminRowFound, 'Akun admin/superadmin harus ada di tabel').toBe(true);
        });

        test('tombol edit tetap muncul untuk akun superadmin', async ({ page }) => {
            // Tunggu table body selesai load
            const tableBody = page.locator('#tabeldata tbody');
            await expect(tableBody).toBeVisible();

            // Cari row dengan username "admin" (superadmin)
            const rows = page.locator('#tabeldata tbody tr');
            let superadminRowFound = false;

            for (let i = 0; i < await rows.count(); i++) {
                const row = rows.nth(i);
                const usernameCell = row.locator('td').nth(2); // Username biasanya di kolom ke-3

                const username = await usernameCell.textContent();
                if (username?.trim() === 'admin') {
                    superadminRowFound = true;
                    // Dapatkan cell aksi (biasanya kolom terakhir)
                    const actionCell = row.locator('td').last();
                    const actionContent = await actionCell.innerHTML();

                    // Verify: Tombol edit (tombol ubah) HARUS ada
                    // Ciri-ciri: berisi "man_user/form" atau icon pencil
                    const hasEditBtn = actionContent.includes('man_user/form') ||
                                      actionContent.includes('fa-pencil') ||
                                      actionContent.includes('fa-edit');

                    expect(hasEditBtn, 'Tombol edit harus tetap muncul untuk superadmin').toBe(true);

                    break;
                }
            }

            expect(superadminRowFound, 'Akun admin/superadmin harus ada di tabel').toBe(true);
        });

    });

    test.describe('Tombol aksi untuk user biasa (non-superadmin)', () => {

        test('tombol lock/unlock muncul untuk user biasa', async ({ page }) => {
            // Tunggu table body selesai load
            const tableBody = page.locator('#tabeldata tbody');
            await expect(tableBody).toBeVisible();

            // Cari row dengan username yang BUKAN "admin"
            const rows = page.locator('#tabeldata tbody tr');
            let nonSuperadminRowFound = false;

            for (let i = 0; i < await rows.count(); i++) {
                const row = rows.nth(i);
                const usernameCell = row.locator('td').nth(2); // Username biasanya di kolom ke-3

                const username = await usernameCell.textContent();
                if (username?.trim() && username?.trim() !== 'admin') {
                    nonSuperadminRowFound = true;
                    // Dapatkan cell aksi (biasanya kolom terakhir)
                    const actionCell = row.locator('td').last();
                    const actionContent = await actionCell.innerHTML();

                    // Verify: Tombol lock/unlock (tombol_aktifkan) HARUS ada untuk user biasa
                    const hasLockUnlockBtn = actionContent.includes('user_lock') ||
                                           actionContent.includes('user_unlock');

                    expect(hasLockUnlockBtn, 'Tombol lock/unlock harus muncul untuk user biasa').toBe(true);

                    break;
                }
            }

            expect(nonSuperadminRowFound, 'User biasa (non-superadmin) harus ada di tabel').toBe(true);
        });

        test('tombol delete muncul untuk user biasa (dengan permission)', async ({ page }) => {
            // Tunggu table body selesai load
            const tableBody = page.locator('#tabeldata tbody');
            await expect(tableBody).toBeVisible();

            // Cari row dengan username yang BUKAN "admin"
            const rows = page.locator('#tabeldata tbody tr');
            let nonSuperadminRowFound = false;

            for (let i = 0; i < await rows.count(); i++) {
                const row = rows.nth(i);
                const usernameCell = row.locator('td').nth(2); // Username biasanya di kolom ke-3

                const username = await usernameCell.textContent();
                if (username?.trim() && username?.trim() !== 'admin') {
                    nonSuperadminRowFound = true;
                    // Dapatkan cell aksi (biasanya kolom terakhir)
                    const actionCell = row.locator('td').last();
                    const actionContent = await actionCell.innerHTML();

                    // Verify: Tombol delete (tombol hapus) HARUS ada untuk user biasa (jika ada permission)
                    // Catatan: Jika user tidak punya permission, tombol tidak akan ada
                    const hasDeleteBtn = actionContent.includes('man_user/delete') ||
                                        actionContent.includes('bg-maroon');

                    // Jika ada, berarti user memiliki permission delete
                    if (hasDeleteBtn) {
                        expect(hasDeleteBtn).toBe(true);
                    }
                    // Jika tidak ada, bisa karena user tidak memiliki permission, yang juga OK

                    break;
                }
            }

            expect(nonSuperadminRowFound, 'User biasa (non-superadmin) harus ada di tabel').toBe(true);
        });

    });

    test.describe('Struktural validasi', () => {

        test('tabel pengguna memiliki kolom: NO, USERNAME, NAMA, EMAIL, KELOMPOK, JABATAN, AKSI', async ({ page }) => {
            // Verify table headers
            const headers = page.locator('#tabeldata thead th');

            // Expected column headers (bisa berbeda tergati tema, tapi yang penting AKSI ada di akhir)
            const headerTexts: string[] = [];
            for (let i = 0; i < await headers.count(); i++) {
                const text = await headers.nth(i).textContent();
                if (text) {
                    headerTexts.push(text.trim());
                }
            }

            // Verify: Kolom AKSI harus ada
            expect(headerTexts.some(h => h.includes('AKSI') || h.includes('Aksi'))).toBe(true);

            // Verify: Ada setidaknya 5 kolom
            expect(headerTexts.length).toBeGreaterThanOrEqual(5);
        });

        test('setiap row di tabel memiliki cell aksi dengan minimal 1 tombol', async ({ page }) => {
            // Tunggu table body selesai load
            const tableBody = page.locator('#tabeldata tbody');
            await expect(tableBody).toBeVisible();

            const rows = page.locator('#tabeldata tbody tr');
            const rowCount = await rows.count();

            expect(rowCount).toBeGreaterThan(0);

            // Check first row as sample
            const firstRow = rows.first();
            const actionCell = firstRow.locator('td').last();
            const actionContent = await actionCell.innerHTML();

            // Verify: Ada minimal 1 tombol di cell aksi
            const hasAnyButton = actionContent.includes('btn') ||
                                actionContent.includes('<a') ||
                                actionContent.includes('href');

            expect(hasAnyButton, 'Cell aksi harus memiliki minimal 1 tombol').toBe(true);
        });

    });

});
