// Issue: #9425 — Terjadi error saat cetak buku administrasi umum — buku agenda surat keluar
// https://github.com/OpenSID/OpenSID/issues/9425
import { test, expect } from '../../../utils/regressionTest';


test.describe('Terjadi error saat cetak Buku Administrasi Umum - Buku Agenda - Surat Keluar', () => {
  test('fix: perbaiki cetak unduh Buku Administrasi Umum - Buku Agenda - Surat Keluar', {
    annotation: {
        type: 'issue',
        description: 'https://github.com/OpenSID/OpenSID/issues/9343'
    }
  }, async ({ page }) => {
    await page.goto('surat_keluar');
    await page.locator('a.btn.dropdown-toggle').click();
    await page.getByRole('link', { name: 'Cetak' }).click();
    await expect(page.locator('#modalBox')).toContainText('Tanggal Cetak', { timeout: 15000 });
    const page1Promise = page.waitForEvent('popup');
    await page.locator('#form-cetak').getByText('Cetak', { exact: true }).click();
    const page1 = await page1Promise;
    await expect(page1.locator('iframe[name="print-frame"]').contentFrame().locator('span')).toContainText('AGENDA SURAT KELUAR');
    await page1.getByRole('link', { name: 'Close' }).click();
    await page.locator('a.btn.dropdown-toggle').click();
    await page.getByRole('link', { name: 'Cetak' }).click();
    await expect(page.locator('#modalBox')).toContainText('Tanggal Cetak', { timeout: 15000 });
  });
});