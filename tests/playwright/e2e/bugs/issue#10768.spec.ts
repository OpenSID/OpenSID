import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Typo "Telelpon" pada Tabel Pelapak #10768', () => {
  test('fix: perbaikan typo Telelpon', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10768',
    },
  }, async ({ page }) => {
    await page.goto('lapak_admin/pelapak');
    await expect(page.getByText('No. Telepon')).toBeVisible();
  });
});
