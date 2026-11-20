// Issue: #9751 — Surat bawaan kode isian tidak tampil
// https://github.com/OpenSID/OpenSID/issues/9751
import { test, expect } from '../../../utils/regressionTest';


test.describe('Bug/error: Surat Bawaan Kode Isian tidak tampil #9751', () => {
  test('fix: perbaiki kode isian kosong saat melihat detail surat', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/9751',
    },
  }, async ({ page }) => {
    await page.goto('surat_master/form/285');
    await page.getByRole('link', { name: 'Form Isian' }).click();
    // pastikan kode isian ada value
    await expect(page.getByRole('row', { name: 'Input Teks Bin Bin Pasangan' }).getByPlaceholder('Masukkan Nama')).toHaveValue('Bin');
    await expect(page.getByRole('row', { name: 'Input Teks Bin Bin Pasangan' }).getByPlaceholder('Masukkan Label')).toHaveValue('Bin Pasangan Pria');
    await expect(page.getByRole('row', { name: 'Input Teks Bin Bin Pasangan' }).getByPlaceholder('Masukkan Placeholder')).toHaveValue('Bin Pasangan Pria');
  });
});