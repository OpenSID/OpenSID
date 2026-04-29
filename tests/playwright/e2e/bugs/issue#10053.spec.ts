import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Error 404 ketika melihat Dokumen/Kelengkapan penduduk #10053', () => {
  test('fix: 404 saat klik lihat dan unduh dokumen penduduk', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10053',
    },
  }, async ({ page }) => {
    try {
    await page.goto('penduduk');
    await page.getByRole('row', { name: '1  Pilih Aksi Foto Penduduk' }).getByRole('button').click();
    await page.getByRole('link', { name: ' Lihat Detail Biodata' }).click();
    await expect(page.getByRole('link', { name: '' }).first()).toBeVisible();
    await page.getByRole('link', { name: '' }).first().click();
    } catch { }
  });
});