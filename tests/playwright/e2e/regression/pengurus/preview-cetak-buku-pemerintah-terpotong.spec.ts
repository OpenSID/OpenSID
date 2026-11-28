// Issue: #10710 — Hasil preview cetak buku pemerintah desa terpotong
// https://github.com/OpenSID/OpenSID/issues/10710
import { test, expect } from '../../../utils/regressionTest';


test.describe('Bug/error: Hasil Previews Cetak Buku Pemerintah Desa Terpotong #10710', () => {
  test('fix: perbaiki Hasil Previews Cetak Buku Pemerintah Desa Terpotong', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10710',
    },
  }, async ({ page }) => {
    await page.goto('pengurus/daftar/cetak');
    
    // Verifikasi style landscape ditambahkan
    // Kita cek apakah CSS rule yang spesifik ada di dalam halaman
    const content = await page.content();
    expect(content).toContain('body.landscape #print-modal');
    expect(content).toContain('width: 1122px');
    expect(content).toContain('overflow: auto !important');
    
    // Verifikasi tabel dimuat (menandakan halaman tidak error)
    await expect(page.locator('table')).toBeVisible();
    await expect(page.getByRole('columnheader', { name: 'NAMA', exact: true })).toBeVisible();
    await expect(page.getByRole('columnheader', { name: 'JABATAN', exact: true })).toBeVisible();
  });
});
