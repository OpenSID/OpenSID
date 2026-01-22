import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Perubahan Alamat KK di penduduk mempengaruhi alamat KK #10623', () => {
  test('fix: perbaikan perubahan alamat KK', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10623',
    },
  }, async ({ page }) => {
    await page.goto('keluarga/anggota/1');
    await expect(page.getByRole('cell', { name: 'test RT 004 / RW - Dusun' })).toBeVisible();
    await page.getByRole('link', { name: '', exact: true }).first().click();
    await expect(page.getByRole('cell', { name: 'Alamat KK' })).toBeVisible();
    await page.getByRole('cell', { name: 'Alamat Sekarang' }).click();
    await expect(page.getByRole('cell', { name: 'Alamat Sekarang' })).toBeVisible();
    await expect(page.getByRole('cell', { name: 'Alamat Sebelumnya' })).toBeVisible();
  });
});
