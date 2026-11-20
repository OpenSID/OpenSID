// Issue: #9618 — Kesalahan penulisan placeholder pada field analisis periode
// https://github.com/OpenSID/OpenSID/issues/9618
import { test, expect } from '../../../utils/regressionTest';


test.describe('Bug/error: Kesalahan Penulisan Placeholder pada Field "Nama Periode" Dan dropdown pendataan #9618', () => {
  test('fix: perbaiki penulisan periode', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/9618',
    },
  }, async ({ page }) => {
    await page.goto('analisis_periode/4');
    await page.getByRole('link', { name: ' Tambah' }).click();
    await expect(page.locator('#validasi')).toContainText('Nama Periode');
  });
});
