// Issue: #9636 — Sort data berdasarkan NIK dan nama penduduk error pada halaman daftar permohonan surat
// https://github.com/OpenSID/OpenSID/issues/9636
import { test, expect } from '../../../utils/regressionTest';


test.describe('Bug/error: Sort data berdasarkan NIK dan Nama Penduduk error pada Halaman Daftar Permohonan Surat #9636', () => {
  test('fix: perbaiki error permohonan surat', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/9636',
    },
  }, async ({ page }) => {
    await page.goto('permohonan_surat_admin');
    await page.waitForSelector('table thead th');
    await page.locator('table thead th', { hasText: 'NIK' }).first().click();
    await page.locator('table thead th', { hasText: 'NAMA PENDUDUK' }).first().click();
    await page.locator('table thead th', { hasText: 'NO HP AKTIF' }).first().click();
    await page.locator('table thead th', { hasText: 'JENIS SURAT' }).first().click();
    await page.locator('table thead th', { hasText: 'TANGGAL KIRIM' }).first().click();
    await page.getByRole('textbox', { name: 'Pilih Status' }).click();
    await page.getByRole('treeitem', { name: 'Belum Lengkap' }).click();
    await page.getByRole('textbox', { name: 'Belum Lengkap' }).click();
    await page.getByRole('treeitem', { name: 'Sudah Diambil' }).click();
  });
});
