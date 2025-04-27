import { test, expect} from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: DETAIL PADA STATISTIK PENDUDUK "KEPEMILIKAN KTP" TIDAK SESUAI #9480', () => {
  test('Fix: perbaiki detail statistik kepemilikan ktp jumlah', {
    annotation: {
        type: 'issue',
        description: 'https://github.com/OpenSID/OpenSID/issues/9480'
    }
  }, async ({ page }) => {
    await page.goto('statistik/penduduk/18');
    await page.waitForSelector('#jml_total');
    await expect(page.locator('#jml_total')).toContainText('1275');
    const page2Promise = page.waitForEvent('popup');
    await page.locator('#jml_total a').click();
    const page2 = await page2Promise;
    await expect(page2.locator('#tabeldata_info')).toContainText('Menampilkan 1 sampai 10 dari 1.275 entri');
  });

  test('Fix: perbaiki detail statistik kepemilikan ktp total', {
    annotation: {
        type: 'issue',
        description: 'https://github.com/OpenSID/OpenSID/issues/9480'
    }
  }, async ({ page }) => {
    await page.goto('statistik/penduduk/18');
    await page.waitForSelector('#total_total');
    await expect(page.locator('#total_total')).toContainText('1440');
    const page2Promise = page.waitForEvent('popup');
    await page.locator('#total_total a').click();
    const page2 = await page2Promise;
    await expect(page2.locator('#tabeldata_info')).toContainText('Menampilkan 1 sampai 10 dari 1.440 entri');
  });
});