import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Infrastruktur Garis tidak tampil Konsisten #10646', () => {
  test('fix: Infrastruktur Garis tidak tampil Konsisten', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10646',
    },
  }, async ({ page }) => {
    // Capture console errors
    const errors: string[] = [];
    page.on('console', msg => {
      if (msg.type() === 'error') {
        errors.push(msg.text());
      }
    });

    await page.goto('/gis/clear');

    // Wait for map to load
    await page.waitForSelector('#map', { timeout: 10000 });

    // Check that "Infrastruktur (Garis)" checkbox exists and is unchecked initially
    const garisCheckbox = page.locator('label').filter({ hasText: 'Infrastruktur (Garis)' }).locator('input[type="checkbox"]');
    await expect(garisCheckbox).toBeVisible();
    await expect(garisCheckbox).not.toBeChecked();

    const initialPaths = page.locator('.leaflet-overlay-pane path');
    const initialCount = await initialPaths.count();
    // Note: This might include other layers, so we compare before/after

    // Click the control panel to open layers
    await page.locator('a[title="Control Panel"]').click();
    await page.locator('a[title="Legenda"]').click();

    // Check the "Infrastruktur (Garis)" checkbox
    await garisCheckbox.check();
    await expect(garisCheckbox).toBeChecked();

    // Wait for garis to appear (paths should increase)
    await page.waitForTimeout(1000); // Allow time for rendering
    const afterCheckCount = await initialPaths.count();
    expect(afterCheckCount).toBeGreaterThan(initialCount); // Assuming garis adds paths

    // Uncheck the checkbox
    await garisCheckbox.uncheck();
    await expect(garisCheckbox).not.toBeChecked();

    // Wait for garis to disappear
    await page.waitForTimeout(1000);
    const afterUncheckCount = await initialPaths.count();
    expect(afterUncheckCount).toBe(initialCount); // Should return to initial count

    // Ensure no console errors occurred
    expect(errors.length).toBe(0);
  });
});
