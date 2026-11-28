// Issue: #10768 — Typo pada daftar pelapak
// https://github.com/OpenSID/OpenSID/issues/10768
import { test, expect } from '../../../utils/regressionTest';


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
