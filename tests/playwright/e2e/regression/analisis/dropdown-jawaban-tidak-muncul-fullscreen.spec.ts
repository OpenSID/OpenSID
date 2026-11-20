// Issue: #9473 — Dropdown pilihan jawaban tidak muncul dalam mode fullscreen
// https://github.com/OpenSID/OpenSID/issues/9473
import { test, expect} from '../../../utils/regressionTest';


test.describe('Dropdown pilihan jawaban tidak muncul dalam mode fullscreen #9473', () => {
  test('fix: dropdown pilihan tetap tampil pada mode fullscreen', {
    annotation: {
        type: 'issue',
        description: 'https://github.com/OpenSID/OpenSID/issues/9473'
    }
  }, async ({ page }) => {
    // Navigate directly to the analisis form — avoids fragile icon-link selector on analisis_master
    // (the icon-only links in the nav have empty accessible names and interfere with .first())
    await page.goto('analisis_respon/4/form/1999');
    await page.getByRole('link', { name: ' Full Screen' }).click();
    await page.locator('.select2-selection__arrow').first().click();
    await page.getByRole('treeitem', { name: 'Kepala Keluarga' }).click();
    await page.locator('tr:nth-child(9) > .col-xs-12 > span > .selection > .select2-selection > .select2-selection__arrow').click();
    await page.getByRole('treeitem', { name: '1. Kawin' }).click();
    await page.locator('tr:nth-child(12) > .col-xs-12 > span > .selection > .select2-selection > .select2-selection__arrow').click();
    await page.getByRole('treeitem', { name: 'Kristen Protestan' }).click();
    await page.getByRole('cell', { name: '0. O' }).locator('b').click();
    await page.getByRole('treeitem', { name: '2. B' }).click();
    await page.locator('tr:nth-child(18) > .col-xs-12 > span > .selection > .select2-selection > .select2-selection__arrow').click();
    await page.getByRole('treeitem', { name: 'Warga Negara Indonesia' }).click();
    await page.locator('tr:nth-child(24) > .col-xs-12 > span > .selection > .select2-selection > .select2-selection__arrow').click();
    await page.getByRole('treeitem', { name: 'Sedang TK/Kelompok Bermain' }).click();
    await page.locator('td').filter({ hasText: 'Pilih Jawaban 1. Petani 2.' }).locator('b').click();
    await page.getByRole('treeitem', { name: 'Buruh Tani' }).click();
    await page.getByRole('combobox', { name: 'Pilih Jawaban' }).locator('span').nth(1).click();
    await page.getByRole('treeitem', { name: '3. Menggunakan alat' }).click();
  });
});
