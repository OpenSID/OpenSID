import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Nilai Input "Kepala Dinas PMD" Tidak Ditampilkan dengan Benar pada Cetakan Surat Pernyataan (#9617)', () => {
  test('fix: Sesuaikan kepala dinas PMD', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/9617',
    },
  }, async ({ page }) => {
    await page.goto('http://localhost:8000/index.php/surat_dinas_cetak/form/sistem-surat-pernyataan');
    await page.getByRole('textbox', { name: 'Isi Nama', exact: true }).click();
    await page.getByRole('textbox', { name: 'Isi Nama', exact: true }).fill('user');
    await page.getByRole('textbox', { name: 'Isi Jabatan' }).click();
    await page.getByRole('textbox', { name: 'Isi Jabatan' }).fill('kades');
    await page.getByRole('textbox', { name: 'Isi Alamat' }).click();
    await page.getByRole('textbox', { name: 'Isi Alamat' }).fill('tes');
    await page.getByRole('textbox', { name: 'Isi Nama Kepala Inspektorat' }).click();
    await page.getByRole('textbox', { name: 'Isi Nama Kepala Inspektorat' }).fill('inspektor');
    await page.getByRole('textbox', { name: 'Isi Kepala Dinas PMD' }).click();
    await page.getByRole('textbox', { name: 'Isi Kepala Dinas PMD' }).fill('kepala dinas ');
    await page.getByRole('textbox', { name: 'Isi Kepala Dinas PMD' }).press('CapsLock');
    await page.getByRole('textbox', { name: 'Isi Kepala Dinas PMD' }).fill('kepala dinas PMD');
    await page.getByRole('textbox', { name: 'Isi Kepala Dinas PMD' }).press('CapsLock');
    await page.getByRole('button', { name: ' Lanjut' }).click();
    await expect(page.locator('iframe[title="Rich Text Area"]').contentFrame().getByLabel('Rich Text Area')).toContainText('kepala dinas PMD');
  });
});
