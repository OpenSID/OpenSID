// Test: Error akses pengaturan pengguna
import { test, expect } from '../../../utils/regressionTest';


test.describe('Bug/error: error akses pengaturan pengguna', () => {
  test('fix: perbaiki error akses pengaturan pengguna', {
  }, async ({ page }) => {
    await page.goto('man_user');

    await expect(page.getByRole('textbox', { name: 'Aktif' })).toBeVisible();
  });
});