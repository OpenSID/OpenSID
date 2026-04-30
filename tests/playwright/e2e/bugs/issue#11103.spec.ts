import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
    storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Tombol Backup Database di Beta dan Localhost tidak muncul saat klik tombol aksi #11103', () => {
    test('fix: perbaikan Tombol Backup Database di Beta dan Localhost tidak muncul saat klik tombol aksi', {
        annotation: {
            type: 'issue',
            description: 'https://github.com/OpenSID/OpenSID/issues/11103',
        },
    }, async ({ page }) => {
        await page.goto('/database');

        // Klik tombol "Pilih Aksi" untuk membuka dropdown backup database
        const pilihAksiBtn = page.locator('a.dropdown-toggle', { hasText: 'Pilih Aksi' });
        await expect(pilihAksiBtn).toBeVisible();
        await pilihAksiBtn.click();

        // Sebelum perbaikan: dropdown kosong untuk user non-super-admin
        // Sesudah perbaikan: opsi "Backup Seluruh Database" harus muncul
        const dropdownMenu = pilihAksiBtn.locator('~ ul.dropdown-menu');
        await expect(dropdownMenu).toBeVisible();

        const backupOption = dropdownMenu.locator('li a', { hasText: /Backup Seluruh Database|Backup Database Desa/ });
        await expect(backupOption).toBeVisible();
        await expect(backupOption).toHaveCount(1);
    });
});
