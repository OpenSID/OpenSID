// Issue: #11133 — Gagal menampilkan data pada halaman rekapitulasi penduduk
// https://github.com/OpenSID/OpenSID/issues/11133
import { test, expect } from '../../../utils/regressionTest';


test.describe('Bug/Error: Gagal menmpilkan data pada halaman rekapitulasi penduduk #11133', () => {
  test('fix: perbaikan menampilkan data pada halaman rekapitulasi penduduk', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11133',
    },
  }, async ({ page }) => {
    await page.goto('bumindes_penduduk_rekapitulasi');
    
    // Pastikan halaman berhasil dimuat
    await expect(page).not.toHaveURL(/error|404|500/);
  });
});
