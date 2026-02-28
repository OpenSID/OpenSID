import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Nama indikator pada Halaman Stunting tidak efektif #9635', () => {
  test('fix: perbaiki nama indikator', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/9635',
    },
  }, async ({ page }) => {
    await page.goto('stunting/scorecard_konvergensi');
    await expect(page.getByRole('rowgroup')).toContainText('Ibu hamil periksa kehamilan paling sedikit 4 kali selama kehamilan.');
  });
});
