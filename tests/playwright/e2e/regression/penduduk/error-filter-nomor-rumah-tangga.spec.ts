// Issue: #10804 — Terjadi error saat klik filter nomor rumah tangga
// https://github.com/OpenSID/OpenSID/issues/10804
import { test, expect } from '../../../utils/regressionTest';


test.describe('Bug/error: terjadi error saat klik filter nomor rumah tangga #10804', () => {
  test('fix: perbaikan filter nomor rumah tangga pada penduduk', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10804',
    },
  }, async ({ page }) => {
    await page.goto('penduduk');

    await page.getByRole('columnheader', { name: 'NO. RUMAH TANGGA: aktifkan' }).click();
    await expect(page.getByRole('columnheader', { name: 'NO. RUMAH TANGGA: aktifkan' })).toBeVisible();
  });
});
