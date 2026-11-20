// Issue: #10654 — Alamat link URL pada tombol optimasi di info sistem tidak sesuai jika tanpa htaccess
// https://github.com/OpenSID/OpenSID/issues/10654
import { test, expect } from '../../../utils/regressionTest';


test.describe('Bug/error: Alamat Link URL pada Tombol Optimasi di Info Sistem sesuaikan jika tidak menggunakan htaccess #10654', () => {
  test('fix: perbaikan alamat link URL', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10654',
    },
  }, async ({ page }) => {
    // Verifikasi link Bersihkan bekerja tanpa index.php
    await page.goto('info_sistem#optimasi');
    await page.getByRole('link', { name: 'Bersihkan' }).first().click();
    await page.waitForLoadState('networkidle');
    await expect(page.locator('#notifikasi')).toContainText('Berhasil Hapus Cache');

    // Verifikasi link Bersihkan juga bekerja dengan URL format index.php
    await page.goto('http://127.0.0.1:8000/index.php/info_sistem#optimasi');
    await page.getByRole('link', { name: 'Bersihkan' }).first().click();
    await page.waitForLoadState('networkidle');
    await expect(page.locator('#notifikasi')).toContainText('Berhasil Hapus Cache');
  });
});
