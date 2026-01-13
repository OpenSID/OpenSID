import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Hasil Previews Cetak Agenda Surat keluar tidak Ada Border #10711', () => {
  test('fix: perbaiki border dan rapihkan tampilan pada cetak agenda surat keluar', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10711',
    },
  }, async ({ page }) => {
    await page.goto('surat_keluar');
    await page.getByText('Cetak/Unduh', { exact: true }).click();
    await page.getByRole('link', { name: ' Cetak' }).click();
    const page1Promise = page.waitForEvent('popup');
    await page.locator('#form-cetak').getByText('Cetak', { exact: true }).click();
    const page1 = await page1Promise;
    await expect(page1.locator('iframe[name="print-frame"]').contentFrame().locator('html')).toBeVisible();
    await expect(page1.locator('iframe[name="print-frame"]').contentFrame().locator('thead')).toMatchAriaSnapshot(`- cell "Nomor Urut"`);
    await expect(page1.locator('iframe[name="print-frame"]').contentFrame().getByText('Nomor Urut Nomor Surat Tanggal Surat Ditujukan Kepada Isi Singkat')).toBeVisible();
  });
});
