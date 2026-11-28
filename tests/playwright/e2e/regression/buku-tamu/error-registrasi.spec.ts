// Issue: #10820 — Error registrasi buku tamu
// https://github.com/OpenSID/OpenSID/issues/10820
import { test, expect } from '../../../utils/regressionTest';


test.describe('Bug/error: Error Registrasi Buku Tamu #10820', () => {
  test(
    'fix: registrasi buku tamu tidak error 500',
    {
      annotation: {
        type: 'issue',
        description: 'https://github.com/OpenSID/OpenSID/issues/10820',
      },
    },
    async ({ page }) => {
      await page.goto('buku-tamu');

      // isi form
      await page.fill('input[name="nama"]', 'Test Playwright');
      await page.fill('input[name="instansi"]', 'QA Automation');
      await page.selectOption('select[name="jenis_kelamin"]', { index: 1 }); // pilih selain "Pilih"
      await page.selectOption('select[name="id_bidang"]', { index: 1 });
      await page.fill('input[name="telepon"]', '081234567890');
      // index 1 is "Lainnya" when no keperluan data exists; fill the extra field
      await page.selectOption('select[name="keperluan"]', { index: 1 });
      await page.fill('textarea[name="alamat"]', 'Alamat testing playwright');
      // If "Lainnya" was selected, fill the additional textarea
      const keperluanLainnya = page.locator('textarea[name="keperluan_lainnya"]');
      if (await keperluanLainnya.isVisible()) {
        await keperluanLainnya.fill('Testing Playwright');
      }

      // submit
      await page.click('button[type="submit"]');

      // pastikan tidak error 500
      await expect(page.locator('text=500')).not.toBeVisible();

      // harus redirect ke halaman kepuasan
      await expect(page).toHaveURL(/buku-tamu\/kepuasan/);

      // halaman kepuasan tampil
      await expect(page.locator('body')).toContainText(/kepuasan|terima kasih/i);
    }
  );
});
