// Issue: #10812 — Perbaiki fungsi pencarian marga agar yang disorot sesuai dengan marga yang dicari
// https://github.com/OpenSID/OpenSID/issues/10812
import { test, expect } from '../../../utils/regressionTest';


test.describe('Bug/error: Perbaiki Fungsi Pencarian marga agar yang disorot sesuai dengan marga yang dicari #10812', () => {
  test('fix: perbaikan select marga form penduduk', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10812',
    },
  }, async ({ page }) => {
    await page.goto('penduduk/form/98');
    // Wait for Select2 to initialize (form is ready when Select2 renders)
    await page.waitForSelector('.select2-container', { timeout: 10000 });

    // Open the Select2 dropdown for marga by clicking its rendered container
    await page.locator('#select2-marga-container').click();
    await page.waitForSelector('.select2-search__field', { state: 'visible' });

    // Type a search query
    await page.locator('.select2-search__field').fill('AA');
    await page.waitForSelector('.select2-results__option--highlighted', { timeout: 5000 });

    // Verify first highlighted item is visible and matches search
    const highlighted = page.locator('.select2-results__option--highlighted').first();
    await expect(highlighted).toBeVisible();
    const text = await highlighted.textContent();
    expect(text?.toLowerCase()).toContain('aa');

    // Select it with Enter
    await page.keyboard.press('Enter');

    // Verify selection is reflected in the select2 rendered value
    const selected = await page.locator('#select2-marga-container').textContent();
    expect(selected?.toLowerCase()).toContain('aa');
  });
});
