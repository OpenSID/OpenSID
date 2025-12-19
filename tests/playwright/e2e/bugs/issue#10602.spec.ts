import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Menu Pembangunan tidak bisa memilih tahun kedepan #10602', () => {
  test('fix: Menu Pembangunan tidak bisa memilih tahun kedepan', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10602',
    },
  }, async ({ page }) => {
    try {
        await page.goto('admin_pembangunan');
        await expect(page.getByRole('heading', { name: 'Pembangunan' })).toBeVisible();
        await page.getByRole('link', { name: ' Tambah' }).click();
        await page.getByText('Tahun Pagu Anggaran').click();

        const currentYear = new Date().getFullYear();

        // ambil semua value option dari <select>
        const options = await page.locator('#tahun_anggaran option').evaluateAll(
            elements => elements.map(el => el.getAttribute('value'))
        );

        // konversi ke angka
        const years = options
            .map(o => Number(o))
            .filter(n => !isNaN(n));

        const maxYear = Math.max(...years);
        const offset = maxYear - currentYear;

        console.log("Tahun terbesar:", maxYear);
        console.log("Offset tahun (tambah):", offset);

        // ============================
        // 1. Tahun sekarang harus muncul
        // ============================
        console.log(`Cek tahun sekarang (${currentYear}) harus muncul =>`, years.includes(currentYear));
        expect(years).toContain(currentYear);

        // ============================
        // 2. Tahun currentYear + offset harus muncul
        // ============================
        console.log(
            `Cek tahun maksimal (${currentYear + offset}) harus muncul =>`,
            years.includes(currentYear + offset)
        );
        expect(years).toContain(currentYear + offset);

        // ============================
        // 3. Tahun currentYear + offset + 1 tidak boleh muncul
        // ============================
        console.log(
            `Cek tahun lebih dari maksimal (${currentYear + offset + 1}) harus TIDAK muncul =>`,
            years.includes(currentYear + offset + 1)
        );
        expect(years).not.toContain(currentYear + offset + 1);


    } catch { }
  });
});