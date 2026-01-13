import { test, expect } from '@playwright/test';

test.describe('Bug/error: Value tahun duplikat pada filter laporan penduduk #10693', () => {
  test('fix: perbaikan Value tahun duplikat pada filter laporan penduduk', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10693',
    },
  }, async ({ page }) => {
    await page.goto('laporan_penduduk');

        // Tunggu sampai halaman dan request ajax selesai dimuat
        await page.waitForURL('**/laporan_penduduk');
        await page.waitForLoadState('networkidle');

        // Asumsi dropdown untuk filter tahun memiliki atribut name="tahun"
        const yearDropdown = page.locator('select[name="tahun"]');
        await expect(yearDropdown).toBeVisible();

        // Mengambil semua elemen <option> dari dropdown
        const options = await yearDropdown.locator('option').all();

        const yearValues = await Promise.all(
        options.map((option) => option.getAttribute('value'))
        );

        // Menghilangkan nilai yang kosong atau placeholder (misal: "Semua Tahun")
        const validYears = yearValues.filter(year => year && year.trim() !== '');

        // Membuat array dengan nilai unik untuk perbandingan
        const uniqueYears = [...new Set(validYears)];

        // Memastikan jumlah tahun yang valid sama dengan jumlah tahun yang unik
        expect(validYears.length, 'Terdapat duplikasi tahun pada filter Laporan Penduduk.').toBe(uniqueYears.length);
  });
});
