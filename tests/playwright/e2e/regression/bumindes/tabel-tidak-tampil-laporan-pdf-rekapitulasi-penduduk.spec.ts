// Issue: #11471 — Laporan PDF Buku Rekapitulasi Jumlah Penduduk tidak tampil (tabel kosong/error) setelah dibuka
// https://github.com/OpenSID/OpenSID/issues/11471
import { test, expect } from '../../../utils/regressionTest';
import fs from 'fs';

test.describe('Bug/Error: Laporan PDF Buku Rekapitulasi Jumlah Penduduk tidak tampil #11471', () => {
  test('fix: Laporan PDF Buku Rekapitulasi Jumlah Penduduk terunduh sebagai PDF valid, bukan halaman error', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11471',
    },
  }, async ({ page }) => {
    await page.goto('bumindes_penduduk_rekapitulasi');
    await expect(page).toHaveURL(/bumindes_penduduk_rekapitulasi/);

    await page.locator('a').filter({ hasText: /Laporan PDF/i }).click();
    await expect(page.locator('#modalBox')).toContainText('Tanggal Cetak', { timeout: 15000 });

    const downloadPromise = page.waitForEvent('download', { timeout: 15000 });
    await page.locator('#form-cetak button[type="submit"]').click();
    const download = await downloadPromise;

    const downloadPath = await download.path();
    expect(downloadPath).toBeTruthy();

    // Bug #11471: view merujuk variabel $paging yang tidak pernah dikirim controller
    // ke render PDF, sehingga render gagal dan file yang diunduh bukan PDF yang valid
    // (tabel tidak tampil saat dibuka). Pastikan file yang terunduh benar-benar PDF.
    const buffer = fs.readFileSync(downloadPath as string);
    expect(buffer.subarray(0, 4).toString()).toBe('%PDF');
  });
});
