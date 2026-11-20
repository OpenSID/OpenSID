// Issue: #9446 — Upload dokumen kependudukan tidak bisa tersimpan
// https://github.com/OpenSID/OpenSID/issues/9446
import { test, expect } from '../../../utils/regressionTest';
import path from 'path';


test.describe('Bug/error: Upload Dokumen Kependudukan tidak bisa tersimpan #9446', () => {
  test('fix: perbaiki upload dokumen penduduk', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/9446',
    },
  }, async ({ page }) => {
    await page.goto('penduduk');
    await page.waitForLoadState('networkidle');
    // Pilih Aksi dropdown uses <a class="dropdown-toggle">, not <button>
    await page.locator('table#tabeldata tbody tr:first-child a.dropdown-toggle').click();
    await page.getByRole('link', { name: ' Upload Dokumen Penduduk' }).click();
    await page.getByRole('link', { name: ' Tambah' }).click();
    await page.getByPlaceholder('Nama Dokumen').click();
    await page.getByPlaceholder('Nama Dokumen').fill('test upload');
    await page.locator('#id_syarat').selectOption('1');

    const filePath = path.resolve(__dirname, '../../../storage/fixtures/dummy.pdf');

    await page.waitForSelector('#file_path');
    await page.locator('#file').setInputFiles(filePath);

    await page.locator('#ok').click();
    await expect(page.locator('#notifikasi')).toContainText('Dokumen berhasil disimpan');
  });
});
