// Issue: #10778 — Typo pekerjaan pada saat tambah data penduduk
// https://github.com/OpenSID/OpenSID/issues/10778
import { test, expect } from '../../../utils/regressionTest';


test.describe('Bug/error: Typo Pekerjaaan pada saat Tambah Data Penduduk #10778', () => {
  test('fix: perbaikan typo Pekerjaaan', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10778',
    },
  }, async ({ page }) => {
    await page.goto('penduduk/form_peristiwa/1');
    // Check "Pekerjaan" label exists (not typo "Pekerjaaan")
    await expect(page.locator('label', { hasText: 'Pekerjaan' }).first()).toBeVisible();
    // Verify the typo "Pekerjaaan" (triple a) is NOT present
    await expect(page.locator('body')).not.toContainText('Pekerjaaan');
  });
});
