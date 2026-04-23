import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
    storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('teknis: perbaiki pilihan desil pada form pemantauan dtsen #11070', () => {
    test('fix: perbaiki pilihan desil pada form pemantauan dtsen', {
        annotation: {
            type: 'issue',
            description: 'https://github.com/OpenSID/OpenSID/issues/11070',
        },
    }, async ({ page }) => {
        page.goto('dtsen/pendataan/form/2');

        await page.getByRole('link', { name: 'V. PETUGAS' }).click();
        await page.locator('#select2-pilihan_2_207-container').click();
        await expect(page.getByRole('treeitem', { name: 'Desil 6' })).toBeVisible();
    });
});