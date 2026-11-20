// Issue: #10777 — Bug laporan perkembangan penduduk (lampiran A-9)
// https://github.com/OpenSID/OpenSID/issues/10777
import { test, expect } from '../../../utils/regressionTest';


test.describe('BUG LAPORAN PERKEMBANGAN PENDUDUK (LAMPIRAN A - 9) #10777', () => {
  test('fix: perbaikan BUG LAPORAN PERKEMBANGAN PENDUDUK (LAMPIRAN A - 9)', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10777',
    },
  }, async ({ page }) => {
    // 1. Masuk ke halaman laporan
    await page.goto('/laporan/cetak/cetak');

    // 2. Pastikan tabel "PERINCIAN PINDAH" terlihat
    const title = page.locator('span:has-text("PERINCIAN PINDAH")');
    await expect(title).toBeVisible();

    // 3. Ambil semua sel dengan class 'bilangan'
    const cells = page.locator('td.bilangan');
    const count = await cells.count();

    // 4. Loop untuk memastikan tidak ada sel yang berisi "-"
    // Jika show_zero_as berfungsi benar (misal harusnya 0, bukan -),
    // maka kita cek setiap cell.
    for (let i = 0; i < count; i++) {
      const cellText = await cells.nth(i).innerText();
      
      // Gagal jika teks adalah "-"
      expect(cellText.trim(), `Kolom bilangan pada index ${i} tidak boleh berisi '-'`).not.toBe('-');
    }
  });
});
