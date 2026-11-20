// Issue: #9481 — Kode isian pendidikan kosong setelah update
// https://github.com/OpenSID/OpenSID/issues/9481
import { test, expect } from '../../../utils/regressionTest';

// Gunakan sesi login admin yang telah disimpan

test.describe('Kode Isian Pendidikan Kosong Setelah Update ke versi 2504.0.1 #9481', () => {
  test('fix: pendidikan penduduk surat dll', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/9481',
    },
  }, async ({ page }) => {
    // 1. Akses halaman surat masuk
    await page.goto('surat');
    
    await page.getByRole('searchbox', { name: 'Cari:' }).click();
    await page.getByRole('searchbox', { name: 'Cari:' }).fill('usaha');
    await page.getByRole('button', { name: ' Buat Surat' }).first().click();
    await page.getByRole('combobox', { name: '-- Cari NIK / Tag ID Card /' }).locator('span').nth(2).click();
    await page.locator('input[type="search"]').fill('a');
    await page.getByRole('treeitem', { name: 'NIK/Tag ID Card : 1505026506840004 - LILIS RIYANI Alamat: RT-, RW- DUSUN' }).locator('div').nth(1).click();
    await expect(page.locator('div:nth-child(3) > .col-sm-4 > .form-control')).toHaveValue('SLTA/SEDERAJAT');
  });
});
