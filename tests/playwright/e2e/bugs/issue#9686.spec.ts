import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Penggunaan Kata Tidak Baku "Kustom Rentang" di Halaman Rekapitulasi Kehadiran #9686', () => {
  test('fix: perbaiki pengisian data survei', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/9686',
    },
  }, async ({ page }) => {
    await page.goto('kehadiran_rekapitulasi');
    await page.locator('#daterange').click();
    await expect(page.locator('#sidebar_collapse')).toContainText('Rentang Khusus');
  });
});