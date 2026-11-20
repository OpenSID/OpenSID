// Issue: #10690 — Hasil cetak filter tahun di buku inventaris dan kekayaan desa
// https://github.com/OpenSID/OpenSID/issues/10690
import { test, expect } from '../../../utils/regressionTest';


test.describe('Bug/error: Hasil Cetak Filter Tahun di Buku Inventaris dan Kekayaan Desa #10690', () => {
  test('fix: Cetak harus menampilkan judul tahun sesuai filter', async ({ page }) => {
    const selectedYear = '2023';

    await page.goto('bumindes_inventaris_kekayaan');

    // Pastikan halaman terbuka
    await expect(page).toHaveURL(/.*bumindes_inventaris_kekayaan/);

    // Lakukan GET ke endpoint cetak dengan parameter tahun (controller membaca dari GET)
    // Menghindari CSRF 403 yang terjadi pada POST tanpa token
    const html = await page.evaluate(async (year) => {
      const res = await fetch(`bumindes_inventaris_kekayaan/cetak?tahun=${encodeURIComponent(year)}`, {
        method: 'GET',
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
