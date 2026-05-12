import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: stunting/pemantauan_paud #11150', () => {
  test('fix: perbaiki tahun default sasaran paud', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11150',
    },
  }, async ({ page }) => {
    await page.goto('stunting/pemantauan_paud');

    await expect(page.getByRole('textbox', { name: '2026' })).toBeVisible();
  });
});