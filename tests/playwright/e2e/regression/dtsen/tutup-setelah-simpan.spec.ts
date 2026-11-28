// Issue: #11068 — Tutup setelah simpan pada form DTSEN
// https://github.com/OpenSID/OpenSID/issues/11068
import { test, expect } from '../../../utils/regressionTest';


test.describe('Bug/error: Tutup setelah simpan #11068', () => {
    test('fix: perbaiki tutup modal anggota keluarga setelah berhasil simpan', {
        annotation: {
            type: 'issue',
            description: 'https://github.com/OpenSID/OpenSID/issues/11068',
        },
    }, async ({ page }) => {
        page.goto('dtsen/pendataan/form/2');

        await page.getByRole('link', { name: 'III. ANGGOTA KELUARGA' }).click();
        await page.getByRole('link', { name: 'Lihat' }).first().click();
        await expect(page.locator('#modal-tab4').getByText('III. ANGGOTA KELUARGA')).toBeVisible();
        await page.locator('#form-4-demografi > .col-sm-12 > .btn').click();
        await expect(page.locator('#modal-tab4').getByText('III. ANGGOTA KELUARGA')).not.toBeVisible();
    });
});