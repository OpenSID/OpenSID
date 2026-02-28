import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Sort data berdasarkan NIK dan Nama Penduduk error pada Halaman Daftar Permohonan Surat #9636', () => {
  test('fix: perbaiki error permohonan surat', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/9636',
    },
  }, async ({ page }) => {
    await page.goto('permohonan_surat_admin');
    await page.getByRole('gridcell', { name: 'NIK: aktifkan untuk' }).click();
    await page.getByRole('gridcell', { name: 'NAMA PENDUDUK: aktifkan untuk' }).click();
    await page.getByRole('gridcell', { name: 'NO HP AKTIF: aktifkan untuk' }).click();
    await page.getByRole('gridcell', { name: 'JENIS SURAT: aktifkan untuk' }).click();
    await page.getByRole('gridcell', { name: 'TANGGAL KIRIM: aktifkan untuk' }).click();
    await page.getByRole('textbox', { name: 'Pilih Status' }).click();
    await page.getByRole('treeitem', { name: 'Belum Lengkap' }).click();
    await page.getByRole('textbox', { name: 'Belum Lengkap' }).click();
    await page.getByRole('treeitem', { name: 'Sudah Diambil' }).click();
  });
});
