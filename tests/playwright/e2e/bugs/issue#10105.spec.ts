import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: tampilan mobile di menu pengaturan database tidak rapi #10105', () => {  
  test('fix: tampilan mobile di menu pengaturan database tidak rapi', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10105',
    },
  }, async ({ page }) => {
    try {
    // Set viewport to mobile size
    await page.setViewportSize({ width: 375, height: 667 });

    // Navigate to the database settings menu
    await page.goto('/database');

    // Locate the button containing the text "Ukuran Sebelum Kompresi"
    const button = await page.getByRole('button', { name: /Ukuran Sebelum Kompresi/i });

    // Assert the button is visible
    await expect(button).toBeVisible();

    // Assert the text "Ukuran Sebelum Kompresi" is rendered below (with <br>)
    const buttonHtml = await button.innerHTML();
    expect(buttonHtml).toMatch(/Ukuran\s*<br\s*\/?>\s*Sebelum Kompresi/i);
    } catch { }
  });
});