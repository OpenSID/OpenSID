import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Tombol loading ketika nonaktifkan data pada pengaturan peta #11216', () => {
  test('fix: perbaiki aksi tombol kunci pada pengaturan peta', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11216',
    },
  }, async ({ page }) => {
    await page.goto('plan');

    await page.getByTitle('Nonaktifkan').first().click();
  });
});