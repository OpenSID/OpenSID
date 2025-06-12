import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Teknis : Tambahkan Keterangan / Informasi pada Pesan Error 404 #8813', () => {
  test('fix: tambah informasi pada pesan error 404', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/8813',
    },
  }, async ({ page }) => {
    await page.goto('http://127.0.0.1:8000/index.php/struktur-organisasi-dan-tata-kerja');
    await expect(page.locator('h1')).toContainText('404');
    await expect(page.getByRole('paragraph')).toContainText('Silakan tambah menu terlebih dahulu.Anda bisa melihat panduan membuat menu di link Panduan');
  });
});
