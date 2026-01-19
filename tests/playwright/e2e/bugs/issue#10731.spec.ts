import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Dropdown pilihan penduduk kosong saat klik tombol Ubah pada Rekam Surat Perseorangan #10731', () => {
  test('fix: perbaiki pilihan penduduk pada rekam surat perseorangan', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10731',
    },
  }, async ({ page }) => {
    await page.goto('keluar/perorangan');
    await page.getByText('-- Cari NIK / Tag ID Card /').click();
    await page.getByText('Alamat: RT--, RW-- DUSUN').first().click();
    await expect(page.getByRole('textbox', { name: 'NIK/Tag ID Card :' })).toBeVisible();
    await page.getByTitle('Ubah Keterangan').click();
    await expect(page.getByText('Ubah Keterangan')).toBeVisible();
    await page.locator('#modalBox').getByText('×').click();
    await expect(page.getByRole('textbox', { name: 'NIK/Tag ID Card :' })).toBeVisible();
  });
});
