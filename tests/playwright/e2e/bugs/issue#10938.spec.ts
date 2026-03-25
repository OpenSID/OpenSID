import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Dokumen yang diupload melalui Layanan Mandiri tidak otomatis dapat diubah oleh warga #10938', () => {
  test('fix: perbaiki dokumen yang diupload warga otomatis dapat diubah', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10938',
    },
  }, async ({ page }) => {
    await page.goto('penduduk/dokumen/98');

    await page.getByTitle('Ubah').click();
    await expect(page.getByLabel('Boleh diubah oleh warga')).toBeVisible();
  });
});
