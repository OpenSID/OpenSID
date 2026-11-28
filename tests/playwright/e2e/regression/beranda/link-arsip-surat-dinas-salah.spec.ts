// Issue: #9594 — Klik arsip surat dinas tidak ke link yang benar
// https://github.com/OpenSID/OpenSID/issues/9594
import { test, expect } from '../../../utils/regressionTest';


test.describe('Bug/error: Ketika Klik Asip Surat Dinas tidak ke link yang benar #9594', () => {
  test('fix: perbaiki migrasi url arsip surat dinas', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/9594'
    }
  }, async ({ page }) => {
    await page.goto('beranda');

    await page.getByRole('link', { name: ' Surat Dinas ' }).click();
    await page.getByRole('link', { name: ' Arsip Surat Dinas' }).click();
    await expect(page.getByRole('heading', { name: 'Arsip Surat Dinas' })).toBeVisible();
  });
});