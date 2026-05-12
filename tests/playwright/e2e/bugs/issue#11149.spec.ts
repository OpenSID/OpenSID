import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
    storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: filter tidak aktif tidak berfungsi pada menu pengguna #11149', () => {
    test('fix: filter tidak aktif tidak berfungsi pada menu pengguna', {
        annotation: {
            type: 'issue',
            description: 'https://github.com/OpenSID/OpenSID/issues/11149',
        },
    }, async ({ page }) => {
        // Buka halaman manajemen pengguna
        await page.goto('man_user');

        // Pastikan tabel termuat
        await expect(page.locator('#tabeldata')).toBeVisible();

        // Klik dropdown filter status dan pilih "Tidak Aktif"
        await page.locator('#select2-status-container').click();
        await page.getByRole('treeitem', { name: 'Tidak Aktif' }).click();

        // Pastikan nilai filter berubah
        await expect(page.locator('#select2-status-container')).toContainText('Tidak Aktif');

        // Tunggu overlay loading datatables menghilang
        await expect(page.locator('#tabeldata_processing')).toHaveCSS('display', 'none');

        // Pastikan pada tabel tidak ditemukan lagi user dengan status "Aktif" (label-success)
        // Tabel seharusnya hanya berisi "Tidak Aktif" atau kosong ("Tidak ada data...")
        const activeUserLabels = page.locator('#tabeldata tbody span.label-success:has-text("Aktif")');
        await expect(activeUserLabels).toHaveCount(0);
    });
});
