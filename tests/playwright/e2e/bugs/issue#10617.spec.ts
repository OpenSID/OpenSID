import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: QrCode pada surat dinas tidak muncul setelah cetak #10617', () => {
  test('fix: perbaiki QrCode pada surat dinas tidak muncul setelah cetak', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10617',
    },
  }, async ({ page }) => {
    await page.goto('surat_master');
    await page.getByRole('link', { name: '' }).nth(2).click();
    await page.getByRole('textbox', { name: 'Masa Berlaku Default' }).click();
    await page.getByRole('textbox', { name: 'Masa Berlaku Default' }).fill('surat dinas');
    await page.getByText('Tampilkan QR Code', { exact: true }).click();
    await page.locator('#lq1').first().click();
    await page.getByRole('link', { name: 'Template' }).click();
    await page.getByRole('button', { name: 'Kode Isian' }).click();
    await page.getByText('Simpan dan Keluar').first().click();
    await page.getByRole('link', { name: ' Cetak Surat' }).click();
    await page.getByRole('searchbox', { name: 'Cari:' }).click();
    await page.getByRole('searchbox', { name: 'Cari:' }).fill('dinas');
    await page.getByRole('button', { name: ' Buat Surat' }).first().dblclick();
    await page.getByText('-- Cari NIK / Tag ID Card /').click();
    await page.locator('input[type="search"]').fill('a');
    await page.getByText('Alamat: RT-001, RW-001 DUSUN BACCARA').first().click();
    await page.getByRole('textbox', { name: 'Pangkat dan Golongan' }).fill('3/a');
    await page.getByRole('textbox', { name: 'Jabatan/Instansi' }).click();
    await page.getByRole('textbox', { name: 'Jabatan/Instansi' }).fill('staf');
    await page.getByRole('textbox', { name: 'Tingkat Biaya Perjalanan' }).click();
    await page.getByRole('textbox', { name: 'Tingkat Biaya Perjalanan' }).fill('4000000');
    await page.getByRole('textbox', { name: 'Maksud Perjalanan Dinas' }).click();
    await page.getByRole('textbox', { name: 'Maksud Perjalanan Dinas' }).fill('jalan jalan');
    await page.getByRole('textbox', { name: 'Tempat Tujuan' }).click();
    await page.getByRole('textbox', { name: 'Tempat Tujuan' }).fill('malaysia');
    await page.getByRole('textbox', { name: 'Alat Angkut Yang Digunakan' }).click();
    await page.getByRole('textbox', { name: 'Alat Angkut Yang Digunakan' }).fill('kapal');
    await page.getByRole('textbox', { name: 'Keterangan Lain' }).click();
    await page.getByRole('textbox', { name: 'Keterangan Lain' }).fill('tidak ada');
    await page.getByRole('textbox', { name: 'Tanggal Berangkat' }).click();
    await page.getByRole('cell', { name: '16' }).click();
    await page.getByRole('textbox', { name: 'Tanggal Kembali' }).click();
    await page.getByRole('cell', { name: '28' }).click();
    await page.getByRole('button', { name: ' Lanjut' }).dblclick();
    await page.getByRole('button', { name: ' Tinjau PDF' }).click();
  });
});
