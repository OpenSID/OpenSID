// Issue: #11240 — Gagal ketika hapus data pengguna terpilih
// https://github.com/OpenSID/OpenSID/issues/11240
import { test, expect } from '../../../utils/regressionTest';


test.describe('Bug/error: Gagal ketika Hapus Data Pengguna terpilih #11240', () => {
  test('fix: perbaiki gagal ketika hapus data pengguna terpilih', {
  }, async ({ page }) => {
    await page.goto('man_user');

    await page.locator('#checkall').check();
    await page.getByRole('button', { name: ' Hapus' }).click();
    await page.getByRole('button', { name: 'Ya, Hapus' }).click();
    await expect(page.getByRole('heading', { name: ' Gagal' })).toBeVisible();
  });
});