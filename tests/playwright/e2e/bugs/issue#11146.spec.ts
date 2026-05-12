import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
    storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Tambah KIA #11146', () => {

    test('fix: POST stunting/getAnak tidak 404', {
        annotation: {
            type: 'issue',
            description: 'https://github.com/OpenSID/OpenSID/issues/11146',
        },
    }, async ({ page }) => {
        // Buka halaman tambah KIA
        await page.goto('stunting/formKia');
        await page.waitForLoadState('networkidle');

        // Pastikan halaman form KIA terbuka dengan benar
        await expect(page.locator('#ibu')).toBeVisible();
        await expect(page.locator('#anak')).toBeVisible();

        // Intercept AJAX call ke getAnak untuk memastikan tidak 404
        const [response] = await Promise.all([
            page.waitForResponse(resp => resp.url().includes('getAnak')),
            // Pilih ibu pertama yang tersedia via Select2
            page.locator('#ibu').evaluate(async (el) => {
                // Trigger select2 open, pilih opsi pertama
                const $select = $(el as HTMLSelectElement);
                // Simulasi AJAX dengan menambah opsi dummy lalu trigger change
                const option = new Option('Test Ibu', '1', true, true);
                $select.append(option).trigger('change');
            }),
        ]);

        // Pastikan endpoint getAnak merespon (bukan 404)
        expect(response.status()).not.toBe(404);
    });

    test('fix: form KIA dapat diakses tanpa error', {
        annotation: {
            type: 'issue',
            description: 'https://github.com/OpenSID/OpenSID/issues/11146',
        },
    }, async ({ page }) => {
        // Buka halaman tambah KIA
        await page.goto('stunting/formKia');
        await page.waitForLoadState('networkidle');

        // Pastikan elemen form ada
        await expect(page.locator('input[name="no_kia"]')).toBeVisible();
        await expect(page.locator('#ibu')).toBeVisible();
        await expect(page.locator('#anak')).toBeVisible();
        await expect(page.locator('#perkiraan_lahir')).toBeVisible();

        // Pastikan dropdown anak dalam kondisi disabled (belum pilih ibu)
        await expect(page.locator('#anak')).toBeDisabled();
    });
});
