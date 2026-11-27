// Issue: #9617 — Nilai input pada form surat dinas cetak
// https://github.com/OpenSID/OpenSID/issues/9617
import { test, expect } from '../../../utils/regressionTest';


test.describe('Nilai Input "Kepala Dinas PMD" Tidak Ditampilkan dengan Benar pada Cetakan Surat Pernyataan (#9617)', () => {
  test('fix: Sesuaikan kepala dinas PMD', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/9617',
    },
  }, async ({ page }) => {
    await page.goto('surat_dinas_cetak/form/sistem-surat-pernyataan');
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
