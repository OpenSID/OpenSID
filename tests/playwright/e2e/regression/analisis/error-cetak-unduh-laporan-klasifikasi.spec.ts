// Issue: #9474 — Error cetak/unduh laporan hasil klasifikasi
// https://github.com/OpenSID/OpenSID/issues/9474
import { test, expect } from '../../../utils/regressionTest';


test.describe('Error cetak/unduh laporan hasil klasifikasi #9474', () => {
  test('fix: perbaiki cetak laporan hasil klasifikasi', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/9474'
    }
  }, async ({ page }) => {
    // Navigasi langsung ke halaman laporan analisis master yang tersedia (ID 3)
    await page.goto('analisis_laporan/3');
    await page.waitForLoadState('networkidle');

    // Verifikasi tombol Cetak/Unduh ada dan bisa diklik
    const cetakUnduhBtn = page.locator('a[data-toggle="dropdown"]').filter({ hasText: 'Cetak/Unduh' });
    await expect(cetakUnduhBtn).toBeVisible({ timeout: 10000 });

    // Buka dropdown Cetak/Unduh
    await cetakUnduhBtn.click();

    // Klik item " Cetak" yang membuka dialog modal
    const cetakLink = page.getByRole('link', { name: ' Cetak' });
    await expect(cetakLink).toBeVisible({ timeout: 5000 });
    await cetakLink.click();

    // Tunggu modal muncul
    await expect(page.locator('#modalBox')).toBeVisible({ timeout: 10000 });

    // Verifikasi modal memiliki judul "Cetak Data"
    await expect(page.locator('#modalBox')).toContainText('Cetak');

    // Tutup modal
    await page.keyboard.press('Escape');
  });
});
