// Issue: #10658 — Saat install baru import penduduk dengan isian dusun lengkap, dusun tidak tampil di wilayah
// https://github.com/OpenSID/OpenSID/issues/10658
import { test, expect } from '../../../utils/regressionTest';

test.describe('Bug/error: Saat Intall Baru Import Penduduk dengan isian Dusun lengkap, Dusun Tidak Tampil di Wilayah Administratif Dusun #10658', () => {
  test('fix: halaman wilayah administratif menampilkan baris dusun', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10658',
    },
  }, async ({ page }) => {
    await page.goto('wilayah');

    // Regresi #10658: dusun hasil import harus tampil di Wilayah Administratif.
    // Tabel DataTable di-render lewat AJAX, jadi tunggu baris data nyata.
    const rows = page.locator('#tabeldata tbody tr');
    await expect(rows.first()).toBeVisible({ timeout: 15000 });

    // Dusun (sebutan "Jorong") dari data seed harus muncul sebagai baris.
    await expect(page.getByRole('cell', { name: 'TABEK BURUAK' })).toBeVisible({ timeout: 15000 });
  });
});
