import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../../../storage/auth/admin.json'),
});

test.describe('Bug/error: Form Tanah Kas Desa #9442', () => {
  test('fix: sesuaikan form tanah kas desa', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/9442',
    },
  }, async ({ page }) => {
    await page.goto('bumindes_tanah_kas_desa');
    await page.getByRole('link', { name: ' Tambah' }).click();
    await page.getByRole('combobox', { name: '-- Pilih Asal Tanah--' }).click();
    await page.getByRole('treeitem', { name: 'APB Desa' }).click();
    await page.locator('#letter_c_persil').click();
    await page.locator('#letter_c_persil').press('CapsLock');
    const randomNumber = Math.floor(Math.random() * 1000000).toString();
    await page.locator('#letter_c_persil').fill(randomNumber);
    await page.getByRole('textbox', { name: '-- Pilih Tipe Tanah--' }).click();
    await page.getByRole('treeitem', { name: 'D-I Lahan Kering Dekat dengan' }).click();
    await page.getByRole('textbox', { name: 'Tanggal Sertifikat' }).click();
    await page.getByRole('cell', { name: '24' }).click();
    await page.locator('#luas').click();
    await page.locator('#luas').fill('100');
    await page.getByRole('textbox', { name: 'Asli Milik Desa' }).click();
    await page.getByRole('textbox', { name: 'Asli Milik Desa' }).fill('100');
    await page.getByRole('textbox', { name: 'Sawah' }).click();
    await page.getByRole('textbox', { name: 'Sawah' }).fill('50');
    await page.getByRole('textbox', { name: 'Tegal' }).click();
    await page.getByRole('textbox', { name: 'Tegal' }).fill('50');
    await page.getByLabel('Pemanfaatan').selectOption('1');
    await page.getByRole('textbox', { name: 'Lokasi' }).click();
    await page.getByRole('textbox', { name: 'Lokasi' }).fill('OKE');
    await page.getByRole('textbox', { name: 'Mutasi' }).click();
    await page.getByRole('textbox', { name: 'Mutasi' }).fill('TEST');
    await page.getByRole('textbox', { name: 'Keterangan' }).click();
    await page.getByRole('textbox', { name: 'Keterangan' }).fill('OKE');
    await page.getByRole('button', { name: ' Simpan' }).click();
    await expect(page.locator('#notifikasi')).toContainText('Berhasil');
  });
});
