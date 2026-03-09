import { test, expect } from '@playwright/test';
import path from 'path';
import { Laravel } from '../../utils/laravel';

test.describe('Bug/error: Tampilan registrasi buku tamu bagian alamat tidak presisi di mode mobile #10881', () => {
  test('fix: perbaikan tampilan alamat di mode mobile', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10881'
    }
  }, async ({ page }) => {
      // Set a mobile viewport size (e.g., iPhone 12)
    await page.setViewportSize({ width: 390, height: 844 });
    
    // Navigate to the guestbook registration form
    await page.goto('buku-tamu');

    const keperluanSelect = page.locator('select[name="keperluan"]');
    const alamatDiv = page.locator('div#alamat');
    const lainnyaDiv = page.locator('div#lainnya');

    // 1. Before selection, the "Lainnya" field should be hidden
    await expect(lainnyaDiv).toBeHidden();

    // 2. Select the "Lainnya" option from the dropdown
    await keperluanSelect.selectOption({ label: 'Lainnya' });

    // 3. Now the "Lainnya" field should be visible
    await expect(lainnyaDiv).toBeVisible();

    // 4. Get the positions of the two fields to verify layout
    const alamatBox = await alamatDiv.boundingBox();
    const lainnyaBox = await lainnyaDiv.boundingBox();

    expect(alamatBox, 'Alamat field bounding box should exist').not.toBeNull();
    expect(lainnyaBox, 'Lainnya field bounding box should exist').not.toBeNull();

    // 5. On mobile, the fields should be stacked.
    // We can verify this by checking that their X-coordinates are almost identical.
    expect(lainnyaBox!.x).toBeCloseTo(alamatBox!.x, 5);
    // And that the "Lainnya" field is positioned below the "Alamat" field.
    expect(lainnyaBox!.y).toBeGreaterThan(alamatBox!.y);
  });
});
