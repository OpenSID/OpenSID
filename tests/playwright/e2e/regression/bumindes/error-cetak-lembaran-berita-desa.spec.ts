// Issue: #9426 — Terjadi error saat cetak buku lembaran desa dan berita desa
// https://github.com/OpenSID/OpenSID/issues/9426
import { test, expect } from '../../../utils/regressionTest';


test.describe('Terjadi error saat cetak Buku Administrasi Umum - Buku Lembaran Desa Dan Berita Desa', () => {
  test('fix: perbaiki cetak unduh Buku Administrasi Umum - Buku Lembaran Desa Dan Berita Desa', {
    annotation: {
        type: 'issue',
        description: 'https://github.com/OpenSID/OpenSID/issues/9426'
    }
  }, async ({ page }) => {
    await page.goto('lembaran_desa');
    await page.locator('a.btn.dropdown-toggle').click();
    await page.getByRole('link', { name: 'Cetak' }).click();
    await expect(page.locator('#modalBox')).toContainText('Tahun', { timeout: 15000 });
    const page1Promise = page.waitForEvent('popup');
    await page.locator('#form-cetak').getByText('Cetak', { exact: true }).click();
    const page1 = await page1Promise;
    await expect(page1.locator('iframe[name="print-frame"]').contentFrame().getByRole('heading', { name: 'BUKU LEMBARAN DESA DAN BERITA DESA' })).toBeVisible();
    await page1.getByRole('link', { name: 'Close' }).click();
    await page.locator('a.btn.dropdown-toggle').click();
    await page.getByRole('link', { name: 'Cetak' }).click();
    await expect(page.locator('#modalBox')).toContainText('Tahun', { timeout: 15000 });
  });
});