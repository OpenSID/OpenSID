// Issue: #9675 — Penggunaan kata tidak baku pada teks petunjuk impor data rumah tangga
// https://github.com/OpenSID/OpenSID/issues/9675
import { test, expect } from '../../../utils/regressionTest';


test.describe('Penggunaan Kata Tidak Baku pada Teks Petunjuk Impor Data Rumah Tangga #9675', () => {
  test('fix: perbaiki Penggunaan Kata Tidak Baku pada Teks Petunjuk Impor Data Rumah Tangga', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/9675',
    },
  }, async ({ page }) => {
    await page.goto('rtm');
    await page.getByRole('link', { name: ' Impor' }).click();
    await expect(page.locator('#impor')).toContainText('data akan secara otomatis terkelompok berdasarkan nomor urut rumah tangga');
    await expect(page.locator('#impor')).toContainText('Pastikan format Excel berekstensi .xlsx (format Excel versi 2007 ke atas)');
  });
});