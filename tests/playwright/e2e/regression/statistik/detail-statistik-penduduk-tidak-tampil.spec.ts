// Issue: #9480 — Detail pada statistik penduduk tidak tampil
// https://github.com/OpenSID/OpenSID/issues/9480
import { test, expect} from '../../../utils/regressionTest';


test.describe('Bug/error: DETAIL PADA STATISTIK PENDUDUK "KEPEMILIKAN KTP" TIDAK SESUAI #9480', () => {
  test('Fix: perbaiki detail statistik kepemilikan ktp jumlah', {
    annotation: {
        type: 'issue',
        description: 'https://github.com/OpenSID/OpenSID/issues/9480'
    }
  }, async ({ page }) => {
    await page.goto('statistik/penduduk/18');
    await page.waitForLoadState('networkidle');
    await expect(page.locator('#jml_total')).not.toBeEmpty();
    const page2Promise = page.waitForEvent('popup');
    await page.locator('#jml_total a').click();
    const page2 = await page2Promise;
    await page2.waitForLoadState('networkidle');
    await expect(page2.locator('#tabeldata_info')).toContainText('Menampilkan');
  });

  test('Fix: perbaiki detail statistik kepemilikan ktp total', {
    annotation: {
        type: 'issue',
        description: 'https://github.com/OpenSID/OpenSID/issues/9480'
    }
  }, async ({ page }) => {
    await page.goto('statistik/penduduk/18');
    await page.waitForLoadState('networkidle');
    await expect(page.locator('#total_total')).not.toBeEmpty();
    const page2Promise = page.waitForEvent('popup');
    await page.locator('#total_total a').click();
    const page2 = await page2Promise;
    await page2.waitForLoadState('networkidle');
    await expect(page2.locator('#tabeldata_info')).toContainText('Menampilkan');
  });
});