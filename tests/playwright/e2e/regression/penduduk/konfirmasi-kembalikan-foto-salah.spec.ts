// Issue: #11157 — Pesan konfirmasi kembalikan foto tidak sesuai (menampilkan informasi penghapusan data)
// https://github.com/OpenSID/OpenSID/issues/11157
import { test, expect } from '../../../utils/regressionTest';


test.describe('[BUG] Pesan Konfirmasi Kembalikan Foto Tidak Sesuai (Menampilkan Informasi Penghapusan Data) #11157', () => {
  test('fix: perbaiki aksi kembalikan foto penduduk', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11157',
    },
  }, async ({ page }) => {
    // Navigate directly to the penduduk form for a known existing penduduk (id=98)
    await page.goto('penduduk/form/98');
    await page.waitForSelector('#maincontent', { timeout: 10000 });
    await page.getByRole('link', { name: ' Kembalikan' }).click();
    await expect(page.locator('#confirm-status')).toContainText('Apakah Anda yakin ingin mengembalikan foto ke foto bawaan?');
  });
});
