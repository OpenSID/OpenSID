import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../../storage/auth/admin.json'),
});

test.describe('Klik batal pada Mutasi Data menutup kolom jenis mutasi #9476', () => {
  test('fix: perbaiki klik batal dan sesuaikan jenis mutasi', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/9476'
    }
  }, async ({ page }) => {
    await page.goto('inventaris_peralatan_mutasi');

    await page.getByRole('link', { name: '' }).nth(1).click();
    await page.getByRole('button', { name: ' Batal' }).click();
    await page.locator('#status').selectOption('Hapus');
    await expect(page.getByText('Jenis Mutasi')).toBeVisible();
    await page.getByLabel('Status Asset').selectOption('Rusak');
    await page.getByRole('button', { name: ' Simpan' }).click();
    await expect(page.getByText('Berhasil Ubah Data')).toBeVisible();
    await expect(page.getByRole('gridcell', { name: 'Rusak' })).toBeVisible();
  });
});