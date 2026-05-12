import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: data tampil semua pada saat cetak data suplemen, padahal sudah difilter #11155', () => {
  test('fix: perbaiki filtering data saat cetak suplemen terdata', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11155',
    },
  }, async ({ page }) => {
    await page.goto('suplemen/rincian/1');

    await page.getByText('Cetak/Unduh').click();
    await page.getByRole('link', { name: ' Cetak' }).click();
    await expect(page.locator('#form-cetak')).toContainText('Opsi Cetak/Unduh Semua Data: Centang untuk memproses data tanpa paginasi. Untuk dataset sangat besar (>10.000 baris), proses mungkin memakan waktu lama atau timeout. Pertimbangkan menggunakan filter atau pencarian untuk mempersempit data terlebih dahulu sebelum cetak/unduh.');
    const page1Promise = page.waitForEvent('popup');
    await page.locator('#form-cetak').getByText('Cetak', { exact: true }).click();
    const page1 = await page1Promise;

    await expect(page2.locator('iframe[name="print-frame"]').contentFrame().getByText('Daftar Terdata Suplemen')).toBeVisible();
  });
});