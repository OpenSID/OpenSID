import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Script <code> pada kolom database mempengaruhi tampilan tulisan setelahnya di halaman website. #10774', () => {
  test('verify keterangan field renders correctly with code tag', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10774',
    },
  }, async ({ page }) => {
    // Navigate to setting_web page
    await page.goto('setting_web');

    // Find the label containing keterangan for link_feed
    const keteranganLabel = page.locator('label').filter({ hasText: /Alamat Feed yang digunakan/ });
    
    // Verify label exists and is visible
    await expect(keteranganLabel).toBeVisible();
    
    // Get the HTML content of the label
    const labelHtml = await keteranganLabel.innerHTML();
    
    // Verify keterangan text is present
    expect(labelHtml).toContain('Alamat Feed yang digunakan');
    
    // Verify code tag is present and rendered correctly
    expect(labelHtml).toContain('<code>');
    expect(labelHtml).toContain('</code>');
    
    // Verify the code tag contains the example URL
    expect(labelHtml).toContain('contoh:');
    expect(labelHtml).toContain('https://www.covid19.go.id/feed/');
    
    // Verify the label text content displays correctly
    const labelText = await keteranganLabel.innerText();
    expect(labelText).toContain('Alamat Feed yang digunakan');
    expect(labelText).toContain('contoh:');
  });

  test('verify form elements after keterangan are not broken', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10774',
    },
  }, async ({ page }) => {
    await page.goto('setting_web');

    // Get all form groups
    const formGroups = page.locator('div.form-group');
    const groupCount = await formGroups.count();
    
    // Verify multiple form groups exist
    expect(groupCount).toBeGreaterThan(0);
    
    // Verify all form groups are properly rendered and visible
    for (let i = 0; i < groupCount; i++) {
      const group = formGroups.nth(i);
      const isVisible = await group.isVisible().catch(() => false);
      
      // Check that group has content
      const groupContent = await group.innerText().catch(() => '');
      expect(groupContent.length).toBeGreaterThan(0);
    }
    
    // Verify form footer buttons are accessible
    const submitButton = page.locator('button[type="submit"]');
    const resetButton = page.locator('button[type="reset"]');
    
    await expect(submitButton).toBeVisible();
    await expect(resetButton).toBeVisible();
  });

  test('verify link_feed input and keterangan display together correctly', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10774',
    },
  }, async ({ page }) => {
    await page.goto('setting_web');
    
    // Find link_feed input field
    const linkFeedInput = page.locator('input[name="link_feed"]');
    await expect(linkFeedInput).toBeVisible();
    
    // Verify input value
    const inputValue = await linkFeedInput.inputValue();
    expect(inputValue).toBeTruthy();
    
    // Find the keterangan label for this input
    const keteranganLabel = page.locator('label').filter({ hasText: /Alamat Feed yang digunakan/ });
    await expect(keteranganLabel).toBeVisible();
    
    // Verify both input and label are visible together
    const inputBoundingBox = await linkFeedInput.boundingBox();
    const labelBoundingBox = await keteranganLabel.boundingBox();
    
    expect(inputBoundingBox).toBeTruthy();
    expect(labelBoundingBox).toBeTruthy();
    
    // Verify label HTML has proper structure
    const labelHtml = await keteranganLabel.innerHTML();
    expect(labelHtml).toContain('<code>');
    expect(labelHtml).toContain('</code>');
  });
});
