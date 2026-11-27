// Issue: #11077 — Gambar tombol cetak/print tidak tampil pada saat cetak lembar disposisi surat
// https://github.com/OpenSID/OpenSID/issues/11077
import { test, expect } from '../../../utils/regressionTest';

test.describe('Bug/error: gambar tombol cetak/print tidak tampil pada saat cetak lembar disposisi surat #11077', () => {
  test('fix: perbaikan toolbar cetak pada lembar disposisi surat dengan landscape orientation', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11077',
    },
  }, async ({ page, context }) => {
    await page.goto('surat_masuk');
    await page.waitForSelector('table#tabeldata tbody tr', { timeout: 15000 });

    // Click cetak lembar disposisi button on the first surat masuk row
    await page.locator('table#tabeldata tbody tr').first()
      .locator('a[title="Cetak Lembar Disposisi Surat"]').click();

    // Modal opens with pamong selection
    await expect(page.locator('#modalBox')).toBeVisible({ timeout: 15000 });

    // Select pamong_ttd and pamong_ketahui (first non-empty option)
    await page.locator('#modalBox select[name="pamong_ttd"]').selectOption({ index: 1 });
    await page.locator('#modalBox select[name="pamong_ketahui"]').selectOption({ index: 1 });

    // Submit opens the disposisi print page in a new tab (form target="_blank")
    const popupPromise = context.waitForEvent('page');
    await page.locator('#modalBox button[type="submit"]').click();
    const popup = await popupPromise;

    await popup.waitForLoadState('networkidle');

    // Verify the print-frame iframe is visible (layout rendered without error)
    await expect(popup.locator('iframe[name="print-frame"]')).toBeVisible({ timeout: 10000 });

    // Verify the print toolbar Close button is present
    // (this is the button whose icon was missing in issue #11077)
    await expect(popup.getByRole('link', { name: 'Close' })).toBeVisible({ timeout: 10000 });

    await popup.close();
  });
});
