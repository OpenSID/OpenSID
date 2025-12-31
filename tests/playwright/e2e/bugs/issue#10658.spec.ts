import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Saat Intall Baru Import Penduduk dengan isian Dusun lengkap, Dusun Tidak Tampil di Wilayah Administratif Dusun #10658', () => {
  test('fix: perbaiki error saat akses halaman wilayah administratif', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10658',
    },
  }, async ({ page }) => {
    await page.goto('wilayah');
    await expect(page.getByRole('gridcell', { name: '' })).toBeVisible();
  });
});