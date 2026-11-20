// Issue: #9667 — Tampilan tombol cetak dan unduh tidak sesuai di laporan
// https://github.com/OpenSID/OpenSID/issues/9667
import { test, expect } from '../../../utils/regressionTest';


test.describe('Bug/error: Tampilan Tombol "Cetak" dan "Unduh" Tidak Sesuai #9667', () => {
  test('fix: perbaiki cetak dan unduh', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/9667',
    },
  }, async ({ page }) => {
    // Navigasi langsung ke halaman laporan analisis master yang tersedia (ID 3)
    await page.goto('analisis_laporan/3');
    await page.waitForLoadState('networkidle');

    // Verifikasi: tombol "Cetak/Unduh" tampil dengan benar (bukan teks acak)
    const cetakUnduhBtn = page.locator('a[data-toggle="dropdown"]').filter({ hasText: 'Cetak/Unduh' });
    await expect(cetakUnduhBtn).toBeVisible({ timeout: 10000 });

    // Verifikasi: dropdown berisi item "Cetak" dan "Unduh"
    await cetakUnduhBtn.click();
    await expect(page.getByRole('link', { name: ' Cetak' })).toBeVisible({ timeout: 5000 });
    await expect(page.getByRole('link', { name: ' Unduh' })).toBeVisible({ timeout: 5000 });
  });
});
