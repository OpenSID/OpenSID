import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Selisih Data KK #9709', () => {
  test('fix: perbaiki Selisih Data KK', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/9709',
    },
  }, async ({ page }) => {
    await page.goto('wilayah');
    await expect(page.getByRole('gridcell', { name: 'KK' })).toBeVisible();
    await page.getByRole('gridcell', { name: '1823' }).click();
    await page.goto('keluarga');
    await expect(page.getByText('Menampilkan 1 sampai 10 dari')).toBeVisible();
    await page.goto('bumindes_penduduk_rekapitulasi');
    await expect(page.getByRole('gridcell', { name: 'JML KK' }).first()).toBeVisible();
    await page.getByRole('gridcell', { name: '1823' }).first().click();
  });
});