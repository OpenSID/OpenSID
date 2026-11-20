// Issue: #11150 — Pemantauan PAUD di stunting
// https://github.com/OpenSID/OpenSID/issues/11150
import { test, expect } from '../../../utils/regressionTest';


test.describe('Bug/error: stunting/pemantauan_paud #11150', () => {
  test('fix: perbaiki tahun default sasaran paud', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11150',
    },
  }, async ({ page }) => {
    await page.goto('stunting/pemantauan_paud');

    await expect(page.getByRole('textbox', { name: '2026' })).toBeVisible();
  });
});