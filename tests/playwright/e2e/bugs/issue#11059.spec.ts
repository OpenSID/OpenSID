import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Data Kesukuan data yang tampil tidak konsisten #11059', () => {
  test('fix: perbaiki tampilan data kesukuan marga tidak tampil otomatis', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11059',
    },
  }, async ({ page }) => {
    await page.goto('keluarga/form_peristiwa/1/1');
  });
});
