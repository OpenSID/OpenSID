import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: terjadi error 500 saat cetak atau unduh kartu keluarga #11079', () => {
  test('fix: perbaikan error 500 saat cetak atau unduh kartu keluarga', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11079',
    },
  }, async ({ page }) => {
    await page.goto('keluarga');

    await page.locator('#checkall').check();
    await page.getByText('Pilih Aksi Lainnya').click();
    await page.getByRole('link', { name: ' Cetak' }).click();
    const page1Promise = page.waitForEvent('popup');
    await page.locator('#form-cetak').getByText('Cetak', { exact: true }).click();
    const page1 = await page1Promise;
    await expect(page1.locator('iframe[name="print-frame"]').contentFrame().getByRole('heading', { name: 'DATA KELUARGA' })).toBeVisible();
  });
});