import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Statistik Akta Kematian #10986', () => {
  test('fix: perbaiki data akta kematian tidak sesuai', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10986',
    },
  }, async ({ page }) => {
    await page.goto('statistik/penduduk/akta-kematian');

    await expect(page.getByRole('columnheader', { name: 'Jenis Kelompok: aktifkan' })).toBeVisible();
  });
});