// Issue: #9482 — Rekam surat perseorangan tidak tampil
// https://github.com/OpenSID/OpenSID/issues/9482
import { test, expect} from '../../../utils/regressionTest';


test.describe('Bug/error: Rekam Surat Perorangan Tidak Tampil', () => {
  test('fix: perbaiki pencarian penduduk pada rekam surat perorangan', {
    annotation: {
        type: 'issue',
        description: 'https://github.com/OpenSID/OpenSID/issues/9482'
    }
  }, async ({ page }) => {
    await page.goto('keluar/perorangan');

    await page.getByText('-- Cari NIK / Tag ID Card /').click();
    await expect(page.getByRole('treeitem', { name: 'NIK/Tag ID Card : 1505024108020003 - KHOIRUN NAJWA Alamat: RT-, RW- DUSUN' }).first()).toBeVisible({ timeout: 15000 });
    await page.getByRole('textbox').nth(2).click();
    await page.getByRole('textbox').nth(2).fill('DWI');
    await expect(page.getByRole('treeitem', { name: 'NIK/Tag ID Card : 1505024404910008 - DWI RAHMADAYANTI Alamat: RT-, RW- DUSUN' }).first()).toBeVisible({ timeout: 15000 });
  });
});