import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Form data Kesukuan di modal ubah biodata penduduk di Cetak Surat tidak bisa diketik huruf #10629', () => {
  test('fix: Form data Kesukuan di modal ubah biodata penduduk di Cetak Surat tidak bisa diketik huruf', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10629',
    },
  }, async ({ page }) => {
    // 1. Navigate to the correct surat form
    await page.goto('/surat/form/sistem-surat-keterangan-domisili');

    // 2. Select a resident. This assumes a test resident with this name exists
    // and does not have a 'suku' value set, so the field is editable.
    await page.locator('span[aria-labelledby="select2-individunik-container"]').click();
    await page.locator('input.select2-search__field').fill('a');
    await page.locator('.select2-results__option').first().waitFor();
    await page.keyboard.press('Enter');

    // 3. Click the button to open the 'ubah biodata' modal
    await page.locator('a.ubah-biodata-individu').click();

    // 4. Wait for the modal to appear and get a handle to it
    const modal = page.locator('#modal-ubah-biodata');
    await expect(modal).toBeVisible();

    // 5. Verify the 'Suku/Etnis' field is enabled and ready for interaction
    const sukuSelect = modal.locator('#suku');
    await expect(sukuSelect).toBeEnabled();

    // 6. Click the 'Suku/Etnis' dropdown to open it
    await modal.locator('span[aria-labelledby="select2-suku-container"]').click();

    // 7. Check that the search input within the dropdown is now visible
    const searchInput = page.locator('body').locator('input.select2-search__field');
    await expect(searchInput).toBeVisible();
    
    // 8. Type a new, unique value to test creating a tag
    const newSuku = 'Melayu ' + Date.now();
    await searchInput.fill(newSuku);

    // 9. Click the result to create and select the new tag
    await page.locator('body').locator('li.select2-results__option', { hasText: newSuku }).click();

    // 10. Assert that the select input now holds the new value
    await expect(sukuSelect).toHaveValue(newSuku);
  });
});
