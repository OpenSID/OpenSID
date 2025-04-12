import { test, expect} from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Terjadi error saat cetak Buku Tanah Kas Desa', () => {
  test('fix: perbaiki cetak unduh Buku Tanah Kas Desa', {
    annotation: {
        type: 'issue',
        description: 'https://github.com/OpenSID/OpenSID/issues/9423'
    }
  }, async ({ page }) => {
    await page.goto('bumindes_tanah_kas_desa');
    await page.getByRole('link', { name: 'Cetak' }).click();
    await expect(page.locator('#modalBox')).toContainText('Buku Tanah Kas Desa');
    const page1Promise = page.waitForEvent('popup');
    await page.locator('#form-cetak').getByText('Cetak', { exact: true }).click();
    const page1 = await page1Promise;
    await expect(page1.locator('iframe[name="print-frame"]').contentFrame().getByRole('heading', { name: 'BUKU TANAH KAS DESA BULAN' })).toBeVisible();
    await page1.getByRole('link', { name: 'Close' }).click();
    await page.getByRole('link', { name: 'Cetak' }).click();
    await expect(page.locator('#modalBox')).toContainText('Buku Tanah Kas Desa');
  });
});