import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../../storage/auth/admin.json'),
});

test.describe('Error cetak/unduh laporan hasil klasifikasi #9474', () => {
  test('fix: perbaiki cetak laporan hasil klasifikasi', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/9474'
    }
  }, async ({ page }) => {
    await page.goto('analisis_master');
    await page.getByRole('link', { name: '' }).first().click();
    await page.getByRole('link', { name: 'Laporan Hasil Klasifikasi' }).click();
    await page.getByRole('link', { name: ' Cetak' }).click();
    const page1Promise = page.waitForEvent('popup');
    await page.getByLabel('Cetak Laporan Hasil Analisis').getByText('Cetak', { exact: true }).click();
    const page1 = await page1Promise;
    await expect(page1.locator('iframe[name="print-frame"]').contentFrame().getByRole('heading', { name: 'Laporan Hasil Analisis' })).toBeVisible();
  });
});