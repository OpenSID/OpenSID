// Issue: #10787 — Tujuan pindah kembali kosong ketika edit data di riwayat mutasi penduduk
// https://github.com/OpenSID/OpenSID/issues/10787
import { test, expect } from '../../../utils/regressionTest';


test.describe('Bug/error: Tujuan pindah kembali kosong ketika edit data di riwayat mutasi penduduk #10787', () => {
  test('fix: perbaikan tujuan pindah kembali kosong ketika edit data di riwayat mutasi penduduk', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10787',
    },
  }, async ({ page }) => {
    await page.goto('penduduk_log');
    await page.waitForSelector('table#tabeldata tbody tr', { timeout: 10000 });
    await expect(page.locator('table#tabeldata tbody tr')).not.toHaveCount(0);

    // Click the edit button on the first row
    await page.locator('table#tabeldata tbody tr:first-child a[data-toggle="modal"]').first().click();

    // Verify the edit modal opens
    const modalEdit = page.locator('#modalBox');
    await expect(modalEdit).toBeVisible({ timeout: 10000 });
    await expect(modalEdit.locator('select, input, textarea').first()).toBeVisible();

    // If ref_pindah (tujuan pindah) is visible, verify it is pre-filled (not empty)
    const dropdownTujuanPindah = modalEdit.locator('select[name="ref_pindah"]');
    if (await dropdownTujuanPindah.isVisible({ timeout: 1000 }).catch(() => false)) {
      await expect(dropdownTujuanPindah).not.toHaveValue('');
    }
  });
});
