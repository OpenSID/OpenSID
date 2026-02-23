import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Error hapus massal Anggota RTM #10680', () => {
  test('fix: perbaikan hapus element sorting pada modal tambah anggota rumah tangga', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10856',
    },
  }, async ({ page }) => {
    await page.goto('rtm/anggota/110387985');

    await page.getByRole('link', { name: ' Tambah' }).click();
    // Tunggu sampai Select2 terinisialisasi sebelum klik
    await page.waitForSelector('.select2-container');
    // Pilih NIK menggunakan interaksi Select2 sesuai best practice Playwright
    await page.locator('#nik').click();
    // Isi pencarian NIK di input Select2 yang muncul
    await page.locator('.select2-container input.select2-search__field').fill('5102101303880001');
    // Pilih hasil yang sesuai
    await page.locator('.select2-results__option').getByText('5102101303880001', { exact: false }).click();

    // Assert kolom 0 (checkbox) tidak bisa diurutkan (tidak ada tombol sort)
    const thCheckbox = await page.locator('#keluarga thead th').first();
    // Pastikan tidak ada elemen sort di dalam th pertama
    await expect(thCheckbox.locator('.sorting, .sorting_asc, .sorting_desc')).toHaveCount(0);
  });
});
