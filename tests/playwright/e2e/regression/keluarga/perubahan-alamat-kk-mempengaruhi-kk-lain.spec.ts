// Issue: #10623 — Perubahan alamat KK di penduduk mempengaruhi alamat KK lain
// https://github.com/OpenSID/OpenSID/issues/10623
import { test, expect } from '../../../utils/regressionTest';


test.describe('Bug/error: Perubahan Alamat KK di penduduk mempengaruhi alamat KK #10623', () => {
  test('fix: perbaikan perubahan alamat KK', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10623',
    },
  }, async ({ page }) => {
    // Buka halaman detail penduduk langsung (penduduk id=98, ID pertama di seed data)
    await page.goto('penduduk/detail/98');
    await page.waitForLoadState('networkidle');
    // Verifikasi bahwa halaman detail menampilkan tiga field alamat yang terpisah
    // (fix #10623: perubahan alamat KK tidak boleh mempengaruhi KK lain)
    await expect(page.getByRole('cell', { name: 'Alamat KK' })).toBeVisible();
    await expect(page.getByRole('cell', { name: 'Alamat Sekarang' })).toBeVisible();
    await expect(page.getByRole('cell', { name: 'Alamat Sebelumnya' })).toBeVisible();
  });
});
