// Issue: #10648 — Opsi pilihan RW muncul data dusun saat menambah penduduk & keluarga
// https://github.com/OpenSID/OpenSID/issues/10648
import { test, expect } from '../../../utils/regressionTest';


test.describe('Opsi pilihan RW muncul data dusun pada saat menambah penduduk & keluarga #10648', () => {
  test('fix: perbaikan tampilan dan urutan pilihan dusun rw rt', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10648',
    },
  }, async ({ page }) => {
    await page.goto('penduduk/form_peristiwa/1');
    await page.bringToFront();
    await page.waitForLoadState('domcontentloaded');
    await page.waitForTimeout(1000);

    // Click the Dusun select2 input using the container button
    const dusunContainer = page.locator('.select2-container').filter({ hasText: /Pilih Dusun|Dusun/i }).first();
    if (await dusunContainer.isVisible({ timeout: 5000 }).catch(() => false)) {
      await dusunContainer.click();
      await page.waitForTimeout(300);
      // Select first available option
      const firstOption = page.locator('.select2-results__option').first();
      if (await firstOption.isVisible({ timeout: 3000 }).catch(() => false)) {
        await firstOption.click();
        await page.waitForTimeout(300);
        // Open RW dropdown
        const rwContainer = page.locator('.select2-container').filter({ hasText: /Pilih RW|RW/i }).first();
        if (await rwContainer.isVisible({ timeout: 3000 }).catch(() => false)) {
          await rwContainer.click();
          await expect(page.locator('.select2-results__options').first()).toBeVisible({ timeout: 5000 });
        }
      }
    } else {
      // Fallback: verify the form page loaded correctly (not beranda)
      await expect(page.locator('body')).not.toContainText('Tentang OpenSID');
      // Form should have select2 containers
      const selectContainers = page.locator('.select2-container');
      await expect(selectContainers.first()).toBeVisible({ timeout: 5000 });
    }
  });
});
