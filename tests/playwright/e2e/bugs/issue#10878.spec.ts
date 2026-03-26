import { test, expect } from '@playwright/test';
import path from 'path';
import { Laravel } from '../../utils/laravel';

test.describe('Bug/error: Inputan Lat Lng pada Pengaturan Peta Lokasi menerima inputan huruf #10878', () => {
  test('fix: perbaikan Inputan Lat Lng pada Pengaturan Peta Lokasi menerima inputan huruf', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10878'
    }
  }, async ({ page }) => {
        // Step 1: Navigate to the main "plan" list page.
        // The URL is based on the controller structure and common routing in the app.
        await page.goto('/plan');

        // Step 2: Dynamically find and click the location map link for the first item.
        // This locator targets the 'Lokasi' link in the first row of the data table.
        const firstItemMapLink = page.locator('table.dataTable tbody tr:first-child a[title="Lokasi"]').first();
        
        // Wait for the link to be visible and click it to open the map editor.
        await expect(firstItemMapLink).toBeVisible({ timeout: 10000 });
        await firstItemMapLink.click();

        // Step 3: Locate the input fields on the map page/modal.
        const latInput = page.locator('input[name="lat"]');
        const lngInput = page.locator('input[name="lng"]');

        // Wait for the inputs to be visible before interacting with them.
        await expect(latInput).toBeVisible();
        await expect(lngInput).toBeVisible();

        // --- VERIFY LATITUDE INPUT ---

        // Test Case 1: Attempt to fill with letters, expect it to be empty.
        await latInput.clear();
        await latInput.fill('should-not-work');
        await expect(latInput).toHaveValue('');

        // Test Case 2: Fill with a valid numeric coordinate, expect it to succeed.
        const validLat = '-7.123456';
        await latInput.fill(validLat);
        await expect(latInput).toHaveValue(validLat);

        // --- VERIFY LONGITUDE INPUT ---

        // Test Case 3: Attempt to fill with letters, expect it to be empty.
        await lngInput.clear();
        await lngInput.fill('this-is-invalid');
        await expect(lngInput).toHaveValue('');

        // Test Case 4: Fill with a valid numeric coordinate, expect it to succeed.
        const validLng = '110.987654';
        await lngInput.fill(validLng);
        await expect(lngInput).toHaveValue(validLng);
        
  });
});
