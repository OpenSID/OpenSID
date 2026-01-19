import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Fungsi Buat Qrcode tanpa logo tidak berfungsi #10736', () => {
  test('fix: perbaiki fungsi Buat Qrcode tanpa logo', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10736',
    },
  }, async ({ page }) => {
    await page.goto('qrcode');

    await page.getByRole('textbox', { name: 'Isi Kode : Kolom ini' }).click();
    await page.getByRole('textbox', { name: 'Isi Kode : Kolom ini' }).fill('qr code tanpa logo');
    await page.getByLabel('Sisipkan Logo :').selectOption('2');
    await page.getByRole('button', { name: ' Buat' }).click();
    await expect(page.getByRole('heading', { name: 'Hasil QR Code' })).toBeVisible();
  });
});
