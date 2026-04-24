import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: tidak ada border pada preview cetak menu statistik #11069', () => {
  test('fix: perbaiki tidak ada border pada preview cetak statistik', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11069',
    },
  }, async ({ page }) => {
    await page.goto('statistik/penduduk/akta-kematian');

    await page.getByRole('link', { name: ' Cetak Data' }).click();
    await page.getByPlaceholder('Laporan No.').click();
    await page.getByPlaceholder('Laporan No.').fill('001');
    const page1Promise = page.waitForEvent('popup');
    await page.getByText('Cetak', { exact: true }).click();
    const page1 = await page1Promise;
    await expect(page1.locator('iframe[name="print-frame"]').contentFrame().getByRole('heading', { name: 'LAPORAN DATA STATISTIK' })).toBeVisible();
  });
});