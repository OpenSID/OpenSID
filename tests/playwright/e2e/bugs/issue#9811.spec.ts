import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Buku Lembaran Desa, Pilihan Cetak per tahun hilang/ tidak ada #9811', () => {
  test('fix: Perbaikan buku Lembaran Desa, Pilihan Cetak per tahun hilang/ tidak ada', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/9811',
    },
  }, async ({ page }) => {
    await page.goto('lembaran_desa');
    await expect(page.getByRole('link', { name: 'Buku Lembaran Desa dan Berita' })).toBeVisible();
    await page.getByRole('textbox', { name: 'Pilih Tahun' }).click();
    await page.getByRole('treeitem', { name: '2025' }).click();
    await page.getByRole('link', { name: ' Cetak' }).click();
    await page.getByTitle('Pilih Tahun').click();
    await page.getByRole('treeitem', { name: '2020' }).click();
    const page1Promise = page.waitForEvent('popup');
    await page.locator('#form-cetak').getByText('Cetak', { exact: true }).click();
    const page1 = await page1Promise;
    await page1.locator('iframe[name="print-frame"]').contentFrame().getByRole('heading', { name: 'BUKU LEMBARAN DESA DAN BERITA' }).click();
  });
});