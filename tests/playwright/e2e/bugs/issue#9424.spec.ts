import { test, expect} from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Terjadi error saat cetak Buku Tanah di Desa', () => {
  test('fix: perbaiki cetak unduh buku tanah di desa', {
    annotation: {
        type: 'issue',
        description: 'https://github.com/OpenSID/OpenSID/issues/9424',
    }
  }, async ({ page }) => {
    await page.goto('bumindes_tanah_desa');
    await page.getByRole('link', { name: 'Cetak' }).click();
    await expect(page.locator('#modalBox')).toContainText('Cetak Buku Tanah di desa');
    const page1Promise = page.waitForEvent('popup');
    await page.locator('#form-cetak').getByText('Cetak', { exact: true }).click();
    const page1 = await page1Promise;
    await expect(page1.locator('iframe[name="print-frame"]').contentFrame().getByRole('heading', { name: 'BUKU TANAH DESA BULAN' })).toBeVisible();
    await page1.getByRole('link', { name: 'Close' }).click();
    await page.getByRole('link', { name: 'Cetak' }).click();
    await expect(page.locator('#modalBox')).toContainText('Cetak Buku Tanah di desa');
  });
});