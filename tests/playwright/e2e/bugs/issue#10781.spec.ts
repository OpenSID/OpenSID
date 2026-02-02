import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: kolom Current Version biasanya readonly, sekarang bisa diedit. #10781', () => {
  test('fix: perbaikan kolom current version bisa diinput', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10781',
    },
  }, async ({ page }) => {
    await page.goto('setting');

    // pastikan field Current Version muncul
    const currentVersion = page.locator('#current_version');
    await expect(currentVersion).toBeVisible();

    // cek type = text
    await expect(currentVersion).toHaveAttribute('type', '"text"');

    // cek field benar-benar disabled
    await expect(currentVersion).toBeDisabled();

  });
});
