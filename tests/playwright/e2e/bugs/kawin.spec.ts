import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
    storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Isian Data Perkawinan', () => {
    test('sembunyikan kolom isian data pelengkap perkawinan sesuai status', async ({ page }) => {
        await page.goto('penduduk/form_peristiwa/5');

        const statusPerkawinan = page.locator('#status_perkawinan');
        const aktaPerkawinan = page.locator('#akta_perkawinan');
        const tanggalPerkawinan = page.locator('input[name="tanggalperkawinan"]');
        const aktaPerceraian = page.locator('#akta_perceraian');
        const tanggalPerceraian = page.locator('input[name="tanggalperceraian"]');

        // Test Belum Kawin (1)
        await statusPerkawinan.selectOption('1');
        await expect(aktaPerkawinan).toBeHidden();
        await expect(tanggalPerkawinan).toBeHidden();
        await expect(aktaPerceraian).toBeHidden();
        await expect(tanggalPerceraian).toBeHidden();

        // Test Kawin (2)
        await statusPerkawinan.selectOption('2');
        await expect(aktaPerkawinan).toBeVisible();
        await expect(tanggalPerkawinan).toBeVisible();
        await expect(aktaPerceraian).toBeHidden();
        await expect(tanggalPerceraian).toBeHidden();

        // Test Cerai Hidup (3)
        await statusPerkawinan.selectOption('3');
        await expect(aktaPerkawinan).toBeHidden();
        await expect(tanggalPerkawinan).toBeHidden();
        await expect(aktaPerceraian).toBeVisible();
        await expect(tanggalPerceraian).toBeVisible();

        // Test Cerai Mati (4)
        await statusPerkawinan.selectOption('4');
        await expect(aktaPerkawinan).toBeHidden();
        await expect(tanggalPerkawinan).toBeHidden();
        await expect(aktaPerceraian).toBeVisible();
        await expect(tanggalPerceraian).toBeVisible();
    });
});