import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Ketika Klik Asip Surat Dinas tidak ke link yang benar #9594', () => {
  test('fix: perbaiki migrasi url arsip surat dinas', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/9594'
    }
  }, async ({ page }) => {
    await page.goto('beranda');

    await page.getByRole('link', { name: ' Surat Dinas ' }).click();
    await page.getByRole('link', { name: ' Arsip Surat Dinas' }).click();
    await expect(page.getByRole('heading', { name: 'Arsip Surat Dinas' })).toBeVisible();
  });
});