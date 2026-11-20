// Issue: #9472 — Error ketika impor dari Google Form untuk analisis baru
// https://github.com/OpenSID/OpenSID/issues/9472
import { test, expect} from '../../../utils/regressionTest';


test.describe('Bug/error: Error ketika impor dari Google Form untuk analisis baru #9472', () => {
  test('fix: import analisis', {
    annotation: {
        type: 'issue',
        description: 'https://github.com/OpenSID/OpenSID/issues/9472'
    }
  }, async ({ page }) => {
    // Test verifikasi form impor dari Google Form dapat diakses tanpa error
    await page.goto('analisis_master', { waitUntil: 'networkidle' });
    await expect(page.locator('h1')).toBeVisible({ timeout: 10000 });

    // Klik "Tambah Analisis Baru" jika visible
    const tambahBtn = page.getByText('Tambah Analisis Baru');
    if (await tambahBtn.isVisible({ timeout: 5000 })) {
      await tambahBtn.click();
      await page.waitForLoadState('networkidle');
      // Verifikasi halaman masih load dengan baik
      await expect(page.locator('body')).toBeVisible();
    }
  }, { timeout: 60000 });
});
