import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Hasil Cetak Filter Tahun di Buku Inventaris dan Kekayaan Desa #10690', () => {
  test('fix: Cetak harus menampilkan judul tahun sesuai filter', async ({ page }) => {
    const selectedYear = '2023';

    await page.goto('bumindes_inventaris_kekayaan');

    // Pastikan halaman terbuka
    await expect(page).toHaveURL(/.*bumindes_inventaris_kekayaan/);

    // Lakukan POST ke endpoint cetak dengan parameter tahun (simulasi submit form target _blank)
    const html = await page.evaluate(async (year) => {
      const res = await fetch(`bumindes_inventaris_kekayaan/cetak?tahun=${encodeURIComponent(year)}`, {
        method: 'POST',
        credentials: 'same-origin',
        headers: {
          'Accept': 'text/html'
        }
      });
      return await res.text();
    }, selectedYear);

    // Pastikan hasil cetak mengandung judul dengan tahun yang dipilih
    expect(html).toContain(`TAHUN ${selectedYear}`);

    // Jika tahun saat ini berbeda, pastikan tidak salah menampilkan tahun lain
    const currentYear = new Date().getFullYear().toString();
    if (currentYear !== selectedYear) {
      expect(html).not.toContain(`TAHUN ${currentYear}`);
    }

    // Pastikan ada tabel hasil cetak (minimal ada tag <table>)
    expect(html).toMatch(/<table[\s\S]*?>[\s\S]*?<\/table>/i);
  });
});
