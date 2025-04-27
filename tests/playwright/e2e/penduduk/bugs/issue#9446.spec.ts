import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../../storage/auth/admin.json'),
});

test.describe('Bug/error: Upload Dokumen Kependudukan tidak bisa tersimpan #9446', () => {
  test('fix: perbaiki upload dokumen penduduk', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/9446',
    },
  }, async ({ page }) => {
    await page.goto('penduduk');
    await page.getByRole('row', { name: '1  Pilih Aksi Foto Penduduk' }).getByRole('button').click();
    await page.getByRole('link', { name: ' Upload Dokumen Penduduk' }).click();
    await page.getByRole('link', { name: ' Tambah' }).click();
    await page.getByPlaceholder('Nama Dokumen').click();
    await page.getByPlaceholder('Nama Dokumen').fill('test upload');
    await page.locator('#id_syarat').selectOption('1');

    // sesuaikan file path dengan file yang ada di komputer anda
    const filePath = path.resolve('C:/Users/habib/Downloads/FORM_1770S_941299737451000_2024_0 (1).pdf');

    await page.waitForSelector('#file_path'); // tunggu input muncul
    await page.locator('#file').setInputFiles(filePath);

    await page.locator('#ok').click();
    await expect(page.locator('#notifikasi')).toContainText('Dokumen berhasil disimpan');
  });
});
