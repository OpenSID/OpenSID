// Issue: #10606 — Di form F1.06 penduduk yang sudah pindah masih muncul datanya
// https://github.com/OpenSID/OpenSID/issues/10606
import { test, expect } from '../../../utils/regressionTest';


test.describe('Bug/error: di form F1.06 Penduduk yang sudah pindah masih muncul datanya #10606', () => {
  test('fix: perbaiki for surat menampilkan data keluarga tidak aktif / pindah', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10606',
    },
  }, async ({ page }) => {
    await page.goto('surat');
    // Cari surat F-1.06 via DataTable search agar tidak bergantung pada urutan halaman pertama
    await page.getByRole('searchbox', { name: 'Cari:' }).fill('Keterangan Beda Identitas');
    await page.waitForTimeout(500);
    await page.getByRole('row', { name: /Keterangan Beda Identitas.*471\.1.*F-1\.06/ }).getByRole('button').first().click();
    await page.getByText('-- Cari NIK / Tag ID Card /').click();
    await page.locator('input[type="search"]').fill('ahlul');
    await page.getByText('NIK/Tag ID Card :').click();
    await expect(page.locator('#validasi div').filter({ hasText: 'NIK Nama Jenis Kelamin Tempat, Tanggal Lahir Hubungan 5201142005716996 AHLUL' }).nth(4)).toBeVisible();
  });
});
