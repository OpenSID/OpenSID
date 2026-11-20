// Issue: #10986 — Statistik akta kematian
// https://github.com/OpenSID/OpenSID/issues/10986
import { test, expect } from '../../../utils/regressionTest';


test.describe('Bug/error: Statistik Akta Kematian #10986', () => {
  test('fix: perbaiki data akta kematian tidak sesuai', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10986',
    },
  }, async ({ page }) => {
    await page.goto('statistik/penduduk/akta-kematian');
    await page.waitForLoadState('networkidle');

    await expect(page.getByRole('columnheader', { name: 'Jenis Kelompok: aktifkan' })).toBeVisible();
  });
});