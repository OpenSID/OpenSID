// Issue: #11216 — Tombol loading ketika nonaktifkan data pada pengaturan peta
// https://github.com/OpenSID/OpenSID/issues/11216
import { test, expect } from '../../../utils/regressionTest';


test.describe('Bug/error: Tombol loading ketika nonaktifkan data pada pengaturan peta #11216', () => {
  test('fix: perbaiki aksi tombol kunci pada pengaturan peta', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11216',
    },
  }, async ({ page }) => {
    await page.goto('plan');

    await page.getByTitle('Nonaktifkan').first().click();
  });
});