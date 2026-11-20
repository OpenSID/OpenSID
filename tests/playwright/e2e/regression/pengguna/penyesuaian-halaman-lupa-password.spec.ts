// Issue: #9560 — Penyesuaian pada halaman lupa password
// https://github.com/OpenSID/OpenSID/issues/9560
import { test, expect } from '../../../utils/regressionTest';


test.describe('unit test Penyesuaian pada halaman lupa password #9560', () => {
  test('fix: lupa kata sandi', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/9560',
    },
  }, async ({ page }) => {
    await page.goto('siteman/lupa_sandi');
    await expect(page.getByRole('strong')).toContainText('Tautan lupa password tidak dapat digunakan karena email atau telegram belum diatur di sistem.');
  });
});
