// Issue: #4824 — NIK tidak tampil di C-Desa
// https://github.com/OpenSID/OpenSID/issues/4824
import { test, expect } from '../../../utils/regressionTest';


test.describe('NIK Tidak Tampil #4824', () => {
  test('fix: perbaikan NIK tidak tampil', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/4824',
    },
  }, async ({ page }) => {
    await page.goto('cdesa');
    await page.getByRole('link', { name: '', exact: true }).click();
    await expect(page.getByRole('textbox', { name: 'NIK Pemilik' })).not.toHaveValue('');
  });
});
