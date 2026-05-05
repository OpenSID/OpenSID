import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Kesalahan menyimpan data enum ke database #11131', () => {
  test('fix: validasi peristiwa tidak valid di form penduduk harus redirect dengan pesan error', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11131',
    },
  }, async ({ page }) => {
    // 1. Akses halaman form penduduk dengan peristiwa tidak valid (111)
    await page.goto('penduduk/form_peristiwa/111');

    // 2. Tunggu redirect ke halaman penduduk
    await page.waitForURL('**/penduduk');

    // 3. Verifikasi pesan error "Peristiwa tidak valid" muncul
    const errorMessage = page.getByText('Peristiwa tidak valid');
    await expect(errorMessage).toBeVisible();
  });
});