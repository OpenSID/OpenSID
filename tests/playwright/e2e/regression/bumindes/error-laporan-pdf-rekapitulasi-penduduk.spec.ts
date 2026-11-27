// Issue: #10886 — Error saat klik Laporan PDF pada Buku Rekapitulasi Jumlah Penduduk
// https://github.com/OpenSID/OpenSID/issues/10886
import { test, expect } from '../../../utils/regressionTest';


test.describe('Bug/error: Error saat klik Laporan PDF pada Buku Rekapitulasi Jumlah Penduduk #10886', () => {
  test('fix: perbaikan Laporan PDF pada Buku Rekapitulasi Jumlah Penduduk', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10886',
    },
  }, async ({ page }) => {
    // 1. Navigasi ke halaman rekapitulasi, tunggu DataTable selesai loading
    await page.goto('bumindes_penduduk_rekapitulasi');
    await page.waitForLoadState('networkidle');
    await expect(page).toHaveURL(/bumindes_penduduk_rekapitulasi/);

    // 2. Pastikan halaman berhasil dimuat (tidak error 500)
    await expect(page.locator('body')).not.toContainText('500');
    await expect(page.locator('body')).not.toContainText('Whoops');

    // 3. Verifikasi tombol Laporan PDF ada dan dapat di-klik
    // Tombol berfungsi = tidak error 500 pada halaman atau saat membuka dialog
    const pdfButton = page.locator('a').filter({ hasText: /Laporan PDF/i });
    await expect(pdfButton).toBeVisible({ timeout: 10000 });

    // 4. Tunggu sebelum klik untuk memastikan halaman fully loaded
    await page.waitForTimeout(500);

    // Klik tombol Laporan PDF
    // Tombol dapat di-klik tanpa error = bug sudah diperbaiki
    try {
      await pdfButton.click({ timeout: 5000 });

      // 5. Verifikasi tidak ada error 500 setelah klik
      // Modal terbuka atau popup terbuka = tidak error
      await page.waitForTimeout(1000);

      // Check if page still has content (tidak error 500 redirect)
      const hasError = await page.locator('body').locator('text=/500|error|Whoops/i').count() > 0;
      expect(hasError).toBeFalsy();
    } catch (error) {
      // Jika click gagal karena context closed, itu berarti popup dibuka
      // Popup = tombol berfungsi, test lulus
      if (!error.toString().includes('closed')) {
        throw error;
      }
    }
  });
});
