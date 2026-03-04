import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: tombol aksi buat surat keterangan lahir mati tidak muncul di riwayat mutasi penduduk #10898', () => {
  test('fix: perbaiki tombol aksi buat surat tidak muncul di riwayat mutasi penduduk', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10898',
    },
  }, async ({ page }) => {
    await page.goto('penduduk_log');

    await expect(page.getByRole('link').nth(4)).toBeVisible();
    await page.getByRole('link').nth(4).click();
    await expect(page.getByText('Surat terkait yang dapat diterbitkan saat terjadi peristiwa kelahiran penduduk')).toBeVisible();
  });
});
