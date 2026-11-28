// Issue: #9709 — Selisih data KK di wilayah
// https://github.com/OpenSID/OpenSID/issues/9709
import { test, expect } from '../../../utils/regressionTest';


test.describe('Bug/error: Selisih Data KK #9709', () => {
  test('fix: perbaiki Selisih Data KK', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/9709',
    },
  }, async ({ page }) => {
    // Regresi #9709: jumlah KK pada Wilayah Administratif harus konsisten dengan
    // data keluarga (tidak "selisih"). Kita verifikasi nilai KK per-dusun sesuai
    // data seed deterministik, lalu pastikan daftar keluarga ikut termuat.
    await page.goto('wilayah');

    // Kolom KK ada pada header tabel wilayah administratif.
    await expect(page.getByRole('columnheader', { name: 'KK', exact: true })).toBeVisible({ timeout: 15000 });

    // Baris jorong "TABEK BURUAK" menampilkan jumlah KK sesuai seed (185).
    const rowJorong = page.locator('#tabeldata tbody tr').filter({ hasText: 'TABEK BURUAK' });
    await expect(rowJorong).toBeVisible({ timeout: 15000 });
    await expect(rowJorong).toContainText('185');

    // Daftar keluarga (sumber data KK) termuat tanpa error.
    await page.goto('keluarga');
    await expect(page.locator('#tabeldata tbody tr').first()).toBeVisible({ timeout: 15000 });
  });
});