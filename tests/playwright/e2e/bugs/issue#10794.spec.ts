import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Nama Penduduk yang sudah Tidak Aktif masih muncul pada Kesehatan Ibu dan Anak #10794', () => {
  test('fix: perbaikan Nama Penduduk yang sudah Tidak Aktif masih muncul pada Kesehatan Ibu dan Anak', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10794',
    },
  }, async ({ page }) => {
    await page.goto('/stunting/formKia');

    // Klik dropdown penduduk
    await page.locator('.select2-container').click();

    // Ketik di search field select2
    const searchBox = page.locator('.select2-search__field');
    await searchBox.fill('Penduduk Meninggal');

    // Tunggu hasil muncul
    await page.waitForTimeout(500);

    // Pastikan penduduk meninggal tidak muncul
    await expect(
        page.locator('.select2-results__option', {
        hasText: 'Penduduk Meninggal',
        })
    ).toHaveCount(0);

    // Cari penduduk hidup
    await searchBox.fill('Penduduk Hidup');

    // Pastikan penduduk hidup muncul
    await expect(
        page.locator('.select2-results__option', {
        hasText: 'Penduduk Hidup',
        })
    ).toBeVisible();
    
  });
});