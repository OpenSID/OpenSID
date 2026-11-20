// Issue: #11107 — Ketika edit surat muncul popup error ajax
// https://github.com/OpenSID/OpenSID/issues/11107
import { test, expect } from '../../../utils/regressionTest';


test.describe('Bug/error: ketika edit surat muncul popup eror ajax #11107', () => {
  test('fix: perbaiki ajax datatable syarat surat', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11107',
    },
  }, async ({ page }) => {
    await page.goto('surat_master/form');
  });
});
