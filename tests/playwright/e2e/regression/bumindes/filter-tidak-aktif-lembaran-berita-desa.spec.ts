// Issue: #10695 — Fungsi filter tidak aktif di buku lembaran desa dan berita desa
// https://github.com/OpenSID/OpenSID/issues/10695
import { test, expect } from '../../../utils/regressionTest';


test.describe('Bug/error: Fungsi Filter Tidak Aktif di Buku Administrasi Umum - Buku Lembaran Desa Dan Berita Desa tidak berfungsi #10695', () => {
  test('fix: perbaikan filter tidak aktif tidak berfungsi', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10695',
    },
  }, async ({ page }) => {
    await page.goto('lembaran_desa');
    await page.waitForLoadState('networkidle');

    // Ubah filter Status ke "Tidak Aktif" via Select2 v4
    // Pattern sesuai dengan test lain yang passing (getByRole combobox + treeitem)
    await page.getByRole('combobox', { name: 'Pilih Status' }).click();
    await page.getByRole('treeitem', { name: 'Tidak Aktif' }).click();

    // Tunggu DataTable reload setelah filter berubah (server-side AJAX)
    await page.waitForLoadState('networkidle');
    // Cari teks di tbody DataTable (DataTables td tidak selalu memiliki role="gridcell")
    await expect(page.locator('#tabeldata tbody').getByText('RPJMDes Miau Merah Tahun 2016').first()).toBeVisible({ timeout: 15000 });
  });
});
