import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Alamat Link URL pada Tombol Optimasi di Info Sistem sesuaikan jika tidak menggunakan htaccess #10654', () => {
  test('fix: perbaikan alamat link URL', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10654',
    },
  }, async ({ page }) => {
    await page.getByRole('button', { name: 'Masuk', exact: true }).click();
    await page.goto('info_sistem#optimasi');
    await page.getByRole('link', { name: 'Bersihkan' }).first().click();
    await expect(page.locator('#notifikasi')).toContainText('Berhasil Hapus Cache');
    await page.getByRole('link', { name: 'Bersihkan' }).nth(1).click();
    await page.goto('http://127.0.0.1:8000/index.php/info_sistem#optimasi');
    await expect(page.locator('#notifikasi')).toContainText('Berhasil Hapus Cache');
  });
});
