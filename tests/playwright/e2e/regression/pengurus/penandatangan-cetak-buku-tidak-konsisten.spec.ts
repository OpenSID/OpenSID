// Issue: #11138 — Pilihan penandatanganan ketika cetak buku pemerintah desa tidak konsisten
// https://github.com/OpenSID/OpenSID/issues/11138
import { test, expect } from '../../../utils/regressionTest';


test.describe('Bug/error: Pilihan penandatanganan ketika klik tombol cetak di buku pemerintah desa tidak konsisten #11138', () => {
    test('fix: perbaikan Pilihan penandatanganan ketika klik tombol cetak di buku pemerintah desa tidak konsisten', {
        annotation: {
            type: 'issue',
            description: 'https://github.com/OpenSID/OpenSID/issues/11138',
        },
    }, async ({ page }) => {
        await page.goto('pengurus');

        // Click the Cetak button
        await page.locator('[title="Cetak Buku Pemerintah Desa"]').click();

        // Wait for modal to appear and check the newly added fields
        await expect(page.locator('select[name="pamong_ttd"]')).toBeVisible();
        await expect(page.locator('select[name="pamong_ketahui"]')).toBeVisible();
    });
});