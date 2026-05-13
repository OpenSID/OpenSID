import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Breadcrumb Daftar Kotak Pesan Mengarah ke Halaman Komentar #11200', () => {
  test('fix: perbaiki breadcrumb daftar kotak pesan', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11193',
    },
  }, async ({ page }) => {
    await page.goto('mailbox');

    await expect(page.getByRole('heading', { name: 'Kotak Pesan' })).toBeVisible();
    await page.getByTitle('Lihat detail pesan').first().click();
    await page.getByRole('link', { name: 'Daftar Kotak Pesan', exact: true }).click();
    await expect(page.getByRole('heading', { name: 'Kotak Pesan' })).toBeVisible();
  });
});