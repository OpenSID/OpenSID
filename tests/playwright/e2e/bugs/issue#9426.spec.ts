import { test, expect} from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Terjadi error saat cetak Buku Administrasi Umum - Buku Lembaran Desa Dan Berita Desa', () => {
  test('fix: perbaiki cetak unduh Buku Administrasi Umum - Buku Lembaran Desa Dan Berita Desa', {
    annotation: {
        type: 'issue',
        description: 'https://github.com/OpenSID/OpenSID/issues/9426'
    }
  }, async ({ page }) => {
    await page.goto('lembaran_desa');
    await page.getByRole('link', { name: 'Cetak' }).click();
    await expect(page.locator('#modalBox')).toContainText('Cetak Laporan');
    const page1Promise = page.waitForEvent('popup');
    await page.locator('#form-cetak').getByText('Cetak', { exact: true }).click();
    const page1 = await page1Promise;
    await expect(page1.locator('iframe[name="print-frame"]').contentFrame().getByRole('heading', { name: 'BUKU LEMBARAN DESA DAN BERITA DESA' })).toBeVisible();
    await page1.getByRole('link', { name: 'Close' }).click();
    await page.getByRole('link', { name: 'Cetak' }).click();
    await expect(page.locator('#modalBox')).toContainText('Cetak Laporan');
  });
});