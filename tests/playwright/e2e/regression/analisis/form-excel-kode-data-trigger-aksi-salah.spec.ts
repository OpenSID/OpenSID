// Issue: #10961 — Klik tombol Form Excel + Kode Data men-trigger aksi tombol Excel + Isi Data
// https://github.com/OpenSID/OpenSID/issues/10961
import { test, expect } from '../../../utils/regressionTest';


test.describe('Bug/error: Klik tombol Form Excel + Kode Data men-trigger aksi tombol Excel + Isi Data #10961', () => {
  test('fix: perbaiki fungsi tombol unduh', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10961',
    },
  }, async ({ page }) => {
    await page.goto('analisis_respon/3');

    await page.getByRole('link', { name: ' Unduh Data' }).click();
    await expect(page.locator('#myModalLabel')).toContainText('Unduh Data');
    await expect(page.locator('#form-cetak')).toContainText('Pilih Jenis File Unduhan');
    await page.getByText('Form Excel + Kode Data').click();
    await page.getByText('Unduh', { exact: true }).click();
  });
});