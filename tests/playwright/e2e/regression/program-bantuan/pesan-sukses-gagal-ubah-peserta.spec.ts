// Issue: #9406 — Pesan sukses/gagal ketika ubah data peserta program bantuan
// https://github.com/OpenSID/OpenSID/issues/9406
import { test, expect } from '../../../utils/regressionTest';


test.describe('Pesan Sukses/Gagal ketika ubah data peserta program bantuan (#9406)', () => {
  test('fix: perbaikan pesan sukses atau gagal ketika ubah data peserta program bantuan', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/9406',
    },
  }, async ({ page }) => {
    // 1. Akses halaman detail peserta bantuan dengan ID 12
    await page.goto('peserta_bantuan/detail_clear/12');

    // 2. Klik tombol edit (ikon pensil)
    await page.getByRole('link', { name: '' }).click();

    // 3. Klik tombol Simpan
    await page.getByText('Simpan', { exact: true }).click();

    // 4. Verifikasi notifikasi sukses
    await expect(page.locator('#notifikasi')).toContainText('Berhasil mengubah data');
  });
});
