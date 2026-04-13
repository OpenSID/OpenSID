import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
    storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Tombol Enter pada keyboard pada saat isi "HAPUS" pada modal alert Konfirmasi Hapus tidak berfungsi #11026', () => {
    test('fix: perbaiki Tombol Enter pada keyboard pada saat isi "HAPUS" pada modal alert Konfirmasi Hapus tidak berfungsi', {
        annotation: {
            type: 'issue',
            description: 'https://github.com/OpenSID/OpenSID/issues/11026',
        },
    }, async ({ page }) => {
        // 1. Ke halaman Gallery (pilih halaman yang memiliki fitur hapus data)
        await page.goto('/gallery');

        // 2. Cari tombol hapus pertama yang memicu modal konfirmasi
        const deleteButton = page.locator('a[data-toggle="modal"][data-target="#confirm-delete"]').first();

        // Jika gallery kosong, mungkin perlu menggunakan halaman lain atau pastikan data ada
        await expect(deleteButton).toBeVisible();
        await deleteButton.click();

        // 3. Pastikan modal konfirmasi muncul
        const modal = page.locator('#confirm-delete');
        await expect(modal).toBeVisible();

        // 4. Pastikan input konfirmasi langsung fokus (autofocus)
        const input = page.locator('#confirm-input');
        await expect(input).toBeFocused();

        // 5. Ketik kata konfirmasi sesuai yang diminta (biasanya "HAPUS")
        const requiredText = await modal.locator('.modal-body').getAttribute('data-confirm-text') || 'HAPUS';
        await input.fill(requiredText);

        // 6. Pastikan tombol Hapus di modal sudah aktif (tidak disabled)
        const okButton = modal.locator('#ok-delete');
        await expect(okButton).not.toBeDisabled();

        // 7. Tekan tombol Enter pada keyboard
        await input.press('Enter');

        // 8. Verifikasi: Modal harus tertutup/hilang
        // Ini membuktikan bahwa Enter berhasil memicu klik pada tombol hapus
        await expect(modal).toBeHidden();
    });
});
