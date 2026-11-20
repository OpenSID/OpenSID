// Issue: #9324 — Slider tidak bisa pilih galeri
// https://github.com/OpenSID/OpenSID/issues/9324
import { test, expect } from '../../../utils/regressionTest';


test.describe('Bug/error: Slider tidak bisa pilih galeri #9324', () => {
  test('fix: perbaiki tidak bisa simpan pilih kategori slider', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/9324',
    },
  }, async ({ page }) => {
    // ke halaman galeri untuk mengecek apakah album sudah ditambahkan ke slider
    await page.goto('gallery');
    const playIcon = page.locator('#dragable .fa.fa-play');
    await expect(playIcon).toBeVisible();
    await page.getByRole('link', { name: ' Slider' }).click();
    await page.getByText('Album Galeri', { exact: true }).click();
    await page.getByRole('button', { name: ' Simpan' }).click();
    await expect(page.locator('#notifikasi')).toContainText('Berhasil Ubah Data');
  });
});
