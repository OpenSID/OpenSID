// Issue: #9635 — Nama indikator pada halaman stunting tidak efektif
// https://github.com/OpenSID/OpenSID/issues/9635
import { test, expect } from '../../../utils/regressionTest';


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
