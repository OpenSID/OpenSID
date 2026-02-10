import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

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
      await page.selectOption('select[name="keperluan"]', { index: 1 });
      await page.fill('textarea[name="alamat"]', 'Alamat testing playwright');

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
