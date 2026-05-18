import { test, expect } from '@playwright/test';

test.describe('Bug/error: Muncul Karakter Aneh pada saat klik tombol lihat di log aktivitas #11208', () => {
    test('fix: Muncul Karakter Aneh pada saat klik tombol lihat di log aktivitas', {
        annotation: {
            type: 'issue',
            description: 'https://github.com/OpenSID/OpenSID/issues/11208',
        },
    }, async ({ page }) => {
        // 1. Navigasi ke halaman Info Sistem tab Log Aktivitas
        await page.goto('info_sistem#log_aktifitas');
        await page.waitForLoadState('networkidle');

        // 2. Pastikan tab/section log aktivitas terlihat
        await expect(page.locator('#log_aktifitas')).toBeVisible();

        // 3. Klik tombol "Lihat" pertama yang tersedia di tabel log aktivitas
        const lihatButton = page.locator('table tbody tr').first().getByRole('button', { name: /lihat/i });
        await lihatButton.click();

        // 4. Tunggu modal logDetailModal muncul
        const modal = page.locator('#logDetailModal');
        await expect(modal).toBeVisible();

        // 5. Verifikasi tombol close modal ada dan terlihat
        const closeButton = modal.locator('button.close[data-dismiss="modal"]');
        await expect(closeButton).toBeVisible();

        // 6. [INTI FIX] Pastikan tombol close TIDAK mengandung karakter aneh "Ã—"
        const closeButtonText = await closeButton.textContent();
        expect(closeButtonText).not.toContain('Ã—');

        // 7. [INTI FIX] Pastikan tombol close menampilkan karakter "×" yang benar
        expect(closeButtonText?.trim()).toBe('×');

        // 8. [INTI FIX] Verifikasi HTML menggunakan &times; atau karakter × yang valid
        const closeButtonHTML = await closeButton.innerHTML();
        const hasValidClose =
            closeButtonHTML.includes('&times;') ||
            closeButtonHTML.includes('×');
        expect(hasValidClose).toBeTruthy();

        // 9. Klik tombol close dan pastikan modal tertutup
        await closeButton.click();
        await expect(modal).toBeHidden();
    });
});