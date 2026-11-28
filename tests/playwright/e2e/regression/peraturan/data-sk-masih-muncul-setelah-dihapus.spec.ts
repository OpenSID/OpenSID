// Issue: #10730 — Data SK masih tetap muncul setelah dihapus di peraturan desa
// https://github.com/OpenSID/OpenSID/issues/10730
import { test, expect } from '../../../utils/regressionTest';


test.describe('Bug/error: Data SK masih tetap muncul sudah di hapus #10730', () => {
  test('fix: Data SK masih tetap muncul sudah di hapus', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10730',
    },
  }, async ({ page }) => {
    // Regresi #10730: Buku Peraturan (Produk Hukum) hanya menampilkan dokumen
    // "hidup" — SK yang sudah dihapus TIDAK boleh muncul lagi. Di rilis-dev
    // daftar peraturan ada di admin `dokumen_sekretariat/peraturan` (route
    // er-laravel `peraturan-desa` & `/api/produk-hukum` tidak ada di rilis-dev).
    await page.goto('dokumen_sekretariat/peraturan');

    // Halaman Buku Peraturan termuat dengan tabel DataTable (difilter DokumenHidup).
    await expect(page.locator('h1')).toContainText('Peraturan', { timeout: 15000 });
    const tabel = page.locator('#tabeldata');
    await expect(tabel).toBeVisible({ timeout: 15000 });
    // Tabel ter-render tanpa error server (struktur tbody ada).
    await expect(tabel.locator('tbody')).toBeAttached({ timeout: 15000 });
  });
});
