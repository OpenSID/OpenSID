import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Tanggal cetak yang dipilih tidak digunakan pada hasil cetak di Buku Administrasi Umum (selalu menampilkan tanggal hari ini) #10974', () => {
  test('fix: perbaiki tanggal cetak yang dipilih tidak digunakan pada cetak', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/#10974',
    },
  }, async ({ page }) => {
    await page.goto('bumindes_tanah_kas_desa');

    await page.getByText('Cetak/Unduh', { exact: true }).click();
    await page.getByRole('link', { name: ' Cetak' }).click();
    await page.locator('#tgl_1').click();
    await page.getByText('1', { exact: true }).nth(1).click();
    const page1Promise = page.waitForEvent('popup');
    await page.locator('#form-cetak').getByText('Cetak', { exact: true }).click();
    const page1 = await page1Promise;
    await expect(page1.locator('iframe[name="print-frame"]').contentFrame().locator('#body')).toContainText('TAMAN BARU, 01 APRIL 2026');
  });
});
