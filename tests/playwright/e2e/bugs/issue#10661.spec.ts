import { test, expect, Page } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Pilihan Kursus dan Bidang Keahlian Buku Kader Pemberdayaan bisa memilih double #10661', () => {
  test('fix: perbaiki ilihan Kursus dan Bidang Keahlian Buku Kader Pemberdayaan bisa memilih double', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10661',
    },
  }, async ({ page }) => {
        const getKursusInput = (page: Page) => page.locator('#kursus + .tokenfield .tokenfield-input');
        const getKursusTokens = (page: Page) => page.locator('#kursus + .tokenfield .token');
        const getAutocompleteSuggestions = (page: Page) => page.locator('ul.ui-autocomplete li.ui-menu-item');

        await page.goto('/bumindes_kader/form');

        const kursusInput = getKursusInput(page);
        const kursusTokens = getKursusTokens(page);

        // 1. Select the first course
        await kursusInput.fill('Komputer');
        await expect(getAutocompleteSuggestions(page)).toHaveCount(1); // Assuming one suggestion "Komputer"
        const firstSuggestionText = await getAutocompleteSuggestions(page).first().textContent();
        await getAutocompleteSuggestions(page).first().click();
        await expect(kursusTokens).toHaveCount(1);
        await expect(kursusTokens.first()).toHaveText(firstSuggestionText!);

        // 2. Verify duplicate suggestion is not shown and suggestions reappear
        // After selecting, the input is still focused, suggestions should immediately show (filtered)
        await expect(getAutocompleteSuggestions(page)).not.toHaveText(firstSuggestionText!); // Filtered
        // Assuming there are no other suggestions initially
        await expect(getAutocompleteSuggestions(page)).toHaveCount(0); 
        
        // 3. Try to add duplicate by typing and selecting
        await kursusInput.fill(firstSuggestionText!);
        await expect(getAutocompleteSuggestions(page)).toHaveCount(0); // Still filtered
        await kursusInput.press('Enter'); // Try to add it
        await expect(kursusTokens).toHaveCount(1); // Should still be 1 token

        // 4. Select a second, different course
        await kursusInput.fill('Bahasa Inggris');
        await expect(getAutocompleteSuggestions(page)).toHaveCount(1); // Assuming "Bahasa Inggris"
        const secondSuggestionText = await getAutocompleteSuggestions(page).first().textContent();
        await getAutocompleteSuggestions(page).first().click();
        await expect(kursusTokens).toHaveCount(2);
        await expect(kursusTokens.nth(1)).toHaveText(secondSuggestionText!);

        // 5. Verify suggestions reappear automatically and are filtered
        // After adding the second token, the input is still focused.
        // Suggestions should appear and not include the two selected ones.
        await expect(getAutocompleteSuggestions(page)).not.toHaveText(firstSuggestionText!);
        await expect(getAutocompleteSuggestions(page)).not.toHaveText(secondSuggestionText!);
        await expect(getAutocompleteSuggestions(page)).toHaveCount(0); // If no other suggestions

        // Clean up: Remove tokens for a clean state if needed for subsequent tests (though this test is self-contained)
        await kursusTokens.locator('.close').first().click(); // Remove first token
        await expect(kursusTokens).toHaveCount(1); // One token remains
        await kursusTokens.locator('.close').first().click(); // Remove second token
        await expect(kursusTokens).toHaveCount(0); // No tokens left
    
  });
});
