import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Tampilan Tombol “Cetak” dan “Unduh” Tidak Sesuai #9667', () => {
  test('fix: perbaiki cetak dan unduh', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/9667',
    },
  }, async ({ page }) => {
    await page.goto('analisis_laporan/2/form/1');
    await page.getByRole('link', { name: ' Cetak' }).click();
    await expect(page.locator('#validasi')).toContainText('Laporan Ditandatangani');
  });
});