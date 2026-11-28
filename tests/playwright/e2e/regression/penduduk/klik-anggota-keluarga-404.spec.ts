// Issue: #5294 — Perbaiki klik anggota keluarga 404
// https://github.com/OpenSID/OpenSID/issues/5294
import { test, expect } from '../../../utils/regressionTest';


test.describe('Bug/error:Perbaiki klik anggota keluarga 404 #5294', () => {
  test('fix: 404 saat klik anggota keluarga', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/5294',
    },
  }, async ({ page }) => {
    // Navigate directly to a penduduk with a valid KK (ZIDAN ZAABIT, id=1999, status_dasar=HIDUP).
    // The 'Anggota Keluarga' button only renders when id_kk is set and status_dasar is HIDUP;
    // the first row in the penduduk listing (SUDIRMAN, id=1920) has no KK so the button is absent.
    // Also avoids waitForLoadState('networkidle') which blocks forever on admin pages.
    await page.goto('penduduk/detail/1999');
    await page.getByRole('link', { name: ' Anggota Keluarga' }).click();
    await expect(page.locator('#maincontent')).toContainText('Daftar Anggota Keluarga');
  });
});
