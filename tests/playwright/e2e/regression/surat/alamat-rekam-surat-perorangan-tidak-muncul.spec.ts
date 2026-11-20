// Issue: #10689 — Alamat pada halaman rekam surat perseorangan tidak muncul
// https://github.com/OpenSID/OpenSID/issues/10689
import { test, expect } from '../../../utils/regressionTest';


test.describe('Bug/error: Alamat pada halaman Rekam Surat Perseorangan tidak muncul #10689', () => {
  test('fix: perbaikan alamat tidak tampil pada rekam surat perseorangan', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10689',
    },
  }, async ({ page }) => {
    await page.goto('keluar/perorangan');
    await page.locator('#select2-nik-container').click();
    // Ketik NIK untuk memfilter hasil di dropdown Select2 (ada banyak penduduk bersurat)
    await page.locator('.select2-search__field').fill('5201142005716996');
    const option = page.getByRole('treeitem', { name: 'NIK/Tag ID Card : 5201142005716996 - AHLUL Alamat: RT-004, RW-- DUSUN MANGSIT' });
    await expect(option).toBeVisible({ timeout: 15000 });
    await option.click();
    await expect(page.getByRole('cell', { name: 'brr cendana v RT 004 / RW -' })).toBeVisible({ timeout: 15000 });
  });
});
