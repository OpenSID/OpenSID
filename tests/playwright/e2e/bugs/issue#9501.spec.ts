import { test, expect} from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Link Program Bantuan pada Detail Penduduk #9501', () => {
  test('fix: perbaiki link program bantuan pada detail penduduk', {
    annotation: {
        type: 'issue',
        description: 'https://github.com/OpenSID/OpenSID/issues/9501'
    }
  }, async ({ page }) => {
    await page.goto('penduduk');

    await page.getByText('Pilih Aksi Lainnya').click();
    await page.getByRole('link', { name: ' Pencarian Program Bantuan' }).click();
    await expect(page.locator('#modalBox').getByText('Pencarian Program Bantuan')).toBeVisible();
    await page.locator('#validasi').getByText('Simpan').click();
    await expect(page.getByRole('gridcell', { name: 'NURKIAH' })).toBeVisible();
    await page.getByRole('gridcell', { name: '1505024101600008' }).click();
    await expect(page.getByRole('cell', { name: 'BLT DD', exact: true })).toBeVisible();
    await page.getByRole('link', { name: 'BLT DD' }).click();
    await expect(page.getByRole('heading', { name: 'Data Peserta Program Bantuan' })).toBeVisible();
    await expect(page.getByRole('cell', { name: 'BLT DD' })).toBeVisible();
  });
});