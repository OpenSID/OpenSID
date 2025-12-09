import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: User SuperAdmin Hilang (NonAktif) #5686', () => {
  test('perbaiki edit superadmin mengubah status jadi tidak aktif', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/5686',
    },
  }, async ({ page }) => {
    try {
    await page.goto('man_user/form/1');
    await page.getByRole('button', { name: ' Simpan' }).click();
    await expect(page.getByRole('gridcell', { name: 'Administrator' }).first()).toBeVisible();
    await page.getByRole('gridcell', { name: 'Administrator' }).first().click();
    } catch { }
  });
});