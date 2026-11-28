import { test, expect } from '../../../utils/regressionTest';


test.describe('Bug/error: Tombol "ke Permohonan Surat" pada saat cetak surat mengarah ke arsip layanan #11253', () => {
  test('fix: perbaiki tombol ubah keterangan "Kembali Ke Arsip Layanan"', {
  }, async ({ page }) => {
    await page.goto('surat');

    await page.getByRole('button', { name: ' Buat Surat' }).first().click();
    await expect(page.getByRole('button', { name: 'ke Permohonan Surat' })).not.toBeVisible();
  });
});