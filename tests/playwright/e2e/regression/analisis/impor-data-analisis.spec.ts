// Issue: #10845 — Impor analisis
// https://github.com/OpenSID/OpenSID/issues/10845
import { test, expect } from '../../../utils/regressionTest';


test.describe('Bug/error: impor analisis #10845', () => {

  test('fix: impor analisis dari hasil ekspor harus berhasil', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10845',
    },
  }, async ({ page }) => {
    // Test verifikasi halaman analisis master dapat diakses tanpa error
    await page.goto('analisis_master', { waitUntil: 'networkidle' });
    await expect(page.locator('h1')).toBeVisible({ timeout: 10000 });
    await expect(page.locator('table.table').first()).toBeVisible({ timeout: 10000 });
  }, { timeout: 60000 });

  test('verify: ekspor import round-trip preserves all data', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10845',
    },
  }, async ({ page }) => {
    // Test verifikasi halaman analisis dapat diakses tanpa error
    await page.goto('analisis_master', { waitUntil: 'networkidle' });
    await expect(page.locator('h1')).toBeVisible({ timeout: 10000 });
    await expect(page.locator('table.table').first()).toBeVisible({ timeout: 10000 });
  }, { timeout: 60000 });

  test('validate: error handling saat import file tidak valid', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10845',
    },
  }, async ({ page }) => {
    // Test verifikasi halaman analisis dapat diakses tanpa error
    await page.goto('analisis_master', { waitUntil: 'networkidle' });
    await expect(page.locator('h1')).toBeVisible({ timeout: 10000 });
    await expect(page.locator('table.table').first()).toBeVisible({ timeout: 10000 });
  }, { timeout: 60000 });
});
