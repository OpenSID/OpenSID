// Issue: #8813 — Tambahkan keterangan/informasi pada pesan error 404
// https://github.com/OpenSID/OpenSID/issues/8813
import { test, expect } from '../../../utils/regressionTest';


test.describe('Teknis : Tambahkan Keterangan / Informasi pada Pesan Error 404 #8813', () => {
  test('fix: tambah informasi pada pesan error 404', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/8813',
    },
  }, async ({ page }) => {
    await page.goto('struktur-organisasi-dan-tata-kerja');
    await expect(page.locator('h1')).toContainText('404');
    await expect(page.getByRole('paragraph')).toContainText('Silakan tambah menu terlebih dahulu.Anda bisa melihat panduan membuat menu di link Panduan');
  });
});
