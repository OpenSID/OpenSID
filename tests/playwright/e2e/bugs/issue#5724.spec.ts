import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Security: TinyMCE Image Plugin - Blind SSRF Prevention (Issue #5724)', () => {
  const testPage = 'http://opensid-premium.test/web/form/agenda';

  test.beforeEach(async ({ page }) => {
    // Navigate to a page with TinyMCE editor
    await page.goto(testPage);
    
    // Wait for TinyMCE to load
    await page.waitForSelector('[data-mce-name="image"]', { timeout: 10000 });
  });

  test('security: Image insert button should open image dialog (Issue #5724)', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/premium/issues/5724 - Blind SSRF in TinyMCE Image Plugin',
    },
  }, async ({ page }) => {
    // Find and click the image button
    const imageButton = page.locator('button[data-mce-name="image"]');
    await expect(imageButton).toBeVisible();
    await imageButton.click();

    // Modal should open
    await page.waitForSelector('.tox-dialog', { timeout: 5000 });
    const dialog = page.locator('.tox-dialog');
    await expect(dialog).toBeVisible();
  });

  test('security: Should reject localhost URL (127.0.0.1) with error message (Issue #5724)', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/premium/issues/5724 - Blind SSRF Prevention',
    },
  }, async ({ page }) => {
    // Open image dialog
    const imageButton = page.locator('button[data-mce-name="image"]');
    await imageButton.click();
    await page.waitForSelector('.tox-dialog');

    // Find the URL input field (input[type="url"] in dialog)
    const urlInput = page.locator('.tox-dialog input[type="url"]');
    
    // Try to input localhost URL
    await urlInput.fill('http://127.0.0.1/image.jpg');
    
    // Trigger validation by moving focus or pressing Enter
    await urlInput.blur();
    
    // Should show error alert
    page.once('dialog', dialog => {
      expect(dialog.message()).toContain('URL tidak diizinkan');
      dialog.accept();
    });

    // Wait a bit for validation to trigger
    await page.waitForTimeout(500);
  });

  test('security: Should reject private IP addresses (192.168.x.x) (Issue #5724)', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/premium/issues/5724 - Private IP Blocking',
    },
  }, async ({ page }) => {
    // Open image dialog
    const imageButton = page.locator('button[data-mce-name="image"]');
    await imageButton.click();
    await page.waitForSelector('.tox-dialog');

    // Find the URL input field
    const urlInput = page.locator('.tox-dialog input[type="url"]');
    
    // Try to input private IP
    await urlInput.fill('http://192.168.1.1/admin/image.jpg');
    await urlInput.blur();

    // Should show error alert
    page.once('dialog', dialog => {
      expect(dialog.message()).toContain('URL tidak diizinkan');
      dialog.accept();
    });

    await page.waitForTimeout(500);
  });

  test('security: Should reject 10.x.x.x internal networks (Issue #5724)', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/premium/issues/5724 - RFC1918 Private IP Blocking',
    },
  }, async ({ page }) => {
    // Open image dialog
    const imageButton = page.locator('button[data-mce-name="image"]');
    await imageButton.click();
    await page.waitForSelector('.tox-dialog');

    // Find the URL input field
    const urlInput = page.locator('.tox-dialog input[type="url"]');
    
    // Try to input 10.x.x.x network
    await urlInput.fill('http://10.0.0.1/metadata');
    await urlInput.blur();

    // Should show error alert
    page.once('dialog', dialog => {
      expect(dialog.message()).toContain('URL tidak diizinkan');
      dialog.accept();
    });

    await page.waitForTimeout(500);
  });

  test('security: Should reject AWS metadata endpoint (169.254.169.254) (Issue #5724)', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/premium/issues/5724 - AWS Metadata Protection',
    },
  }, async ({ page }) => {
    // Open image dialog
    const imageButton = page.locator('button[data-mce-name="image"]');
    await imageButton.click();
    await page.waitForSelector('.tox-dialog');

    // Find the URL input field
    const urlInput = page.locator('.tox-dialog input[type="url"]');
    
    // Try to input AWS metadata endpoint
    await urlInput.fill('http://169.254.169.254/latest/meta-data/');
    await urlInput.blur();

    // Should show error alert
    page.once('dialog', dialog => {
      expect(dialog.message()).toContain('URL tidak diizinkan');
      dialog.accept();
    });

    await page.waitForTimeout(500);
  });

  test('security: Should reject file:// protocol URLs (Issue #5724)', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/premium/issues/5724 - Dangerous Protocol Blocking',
    },
  }, async ({ page }) => {
    // Open image dialog
    const imageButton = page.locator('button[data-mce-name="image"]');
    await imageButton.click();
    await page.waitForSelector('.tox-dialog');

    // Find the URL input field
    const urlInput = page.locator('.tox-dialog input[type="url"]');
    
    // Try to input file protocol
    await urlInput.fill('file:///etc/passwd');
    await urlInput.blur();

    // Should show error alert
    page.once('dialog', dialog => {
      expect(dialog.message()).toContain('URL tidak diizinkan');
      dialog.accept();
    });

    await page.waitForTimeout(500);
  });

  test('security: Should reject gopher:// protocol (Issue #5724)', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/premium/issues/5724 - Dangerous Protocol Blocking',
    },
  }, async ({ page }) => {
    // Open image dialog
    const imageButton = page.locator('button[data-mce-name="image"]');
    await imageButton.click();
    await page.waitForSelector('.tox-dialog');

    // Find the URL input field
    const urlInput = page.locator('.tox-dialog input[type="url"]');
    
    // Try to input gopher protocol
    await urlInput.fill('gopher://internal-server');
    await urlInput.blur();

    // Should show error alert
    page.once('dialog', dialog => {
      expect(dialog.message()).toContain('URL tidak diizinkan');
      dialog.accept();
    });

    await page.waitForTimeout(500);
  });

  test('security: Should accept HTTPS URLs from whitelisted CDN domains (Issue #5724)', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/premium/issues/5724 - Whitelist CDN Domains',
    },
  }, async ({ page }) => {
    // Open image dialog
    const imageButton = page.locator('button[data-mce-name="image"]');
    await imageButton.click();
    await page.waitForSelector('.tox-dialog');

    // Find the URL input field
    const urlInput = page.locator('.tox-dialog input[type="url"]');
    
    // Input valid CDN URL
    await urlInput.fill('https://imgur.com/abc123.jpg');
    
    // Should NOT show error alert for valid URL
    let errorShown = false;
    page.once('dialog', () => {
      errorShown = true;
    });

    await urlInput.blur();
    await page.waitForTimeout(500);

    expect(errorShown).toBe(false);
  });

  test('security: Should accept Cloudinary images (Issue #5724)', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/premium/issues/5724 - Whitelist Cloudinary',
    },
  }, async ({ page }) => {
    // Open image dialog
    const imageButton = page.locator('button[data-mce-name="image"]');
    await imageButton.click();
    await page.waitForSelector('.tox-dialog');

    // Find the URL input field
    const urlInput = page.locator('.tox-dialog input[type="url"]');
    
    // Input valid Cloudinary URL
    await urlInput.fill('https://res.cloudinary.com/demo/image/upload/w_400/sample.jpg');
    
    // Should NOT show error alert
    let errorShown = false;
    page.once('dialog', () => {
      errorShown = true;
    });

    await urlInput.blur();
    await page.waitForTimeout(500);

    expect(errorShown).toBe(false);
  });

  test('security: Should accept Google Drive images (Issue #5724)', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/premium/issues/5724 - Whitelist Google Drive',
    },
  }, async ({ page }) => {
    // Open image dialog
    const imageButton = page.locator('button[data-mce-name="image"]');
    await imageButton.click();
    await page.waitForSelector('.tox-dialog');

    // Find the URL input field
    const urlInput = page.locator('.tox-dialog input[type="url"]');
    
    // Input valid Google Drive URL
    await urlInput.fill('https://drive.google.com/file/d/abc123/view');
    
    // Should NOT show error alert
    let errorShown = false;
    page.once('dialog', () => {
      errorShown = true;
    });

    await urlInput.blur();
    await page.waitForTimeout(500);

    expect(errorShown).toBe(false);
  });

  test('security: Should accept data: URLs (Issue #5724)', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/premium/issues/5724 - Allow Data URLs',
    },
  }, async ({ page }) => {
    // Open image dialog
    const imageButton = page.locator('button[data-mce-name="image"]');
    await imageButton.click();
    await page.waitForSelector('.tox-dialog');

    // Find the URL input field
    const urlInput = page.locator('.tox-dialog input[type="url"]');
    
    // Input valid data URL
    await urlInput.fill('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==');
    
    // Should NOT show error alert
    let errorShown = false;
    page.once('dialog', () => {
      errorShown = true;
    });

    await urlInput.blur();
    await page.waitForTimeout(500);

    expect(errorShown).toBe(false);
  });

  test('security: Should reject unknown domains not in whitelist (Issue #5724)', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/premium/issues/5724 - Domain Whitelist Enforcement',
    },
  }, async ({ page }) => {
    // Open image dialog
    const imageButton = page.locator('button[data-mce-name="image"]');
    await imageButton.click();
    await page.waitForSelector('.tox-dialog');

    // Find the URL input field
    const urlInput = page.locator('.tox-dialog input[type="url"]');
    
    // Try to input unknown domain (not in whitelist)
    await urlInput.fill('https://unknown-random-domain.com/image.jpg');
    await urlInput.blur();

    // Should show error alert for unknown domain
    page.once('dialog', dialog => {
      expect(dialog.message()).toContain('URL tidak diizinkan');
      dialog.accept();
    });

    await page.waitForTimeout(500);
  });

  test('security: Should reject reserved hostnames like example.com (Issue #5724)', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/premium/issues/5724 - Reserved Hostname Blocking',
    },
  }, async ({ page }) => {
    // Open image dialog
    const imageButton = page.locator('button[data-mce-name="image"]');
    await imageButton.click();
    await page.waitForSelector('.tox-dialog');

    // Find the URL input field
    const urlInput = page.locator('.tox-dialog input[type="url"]');
    
    // Try to input example.com
    await urlInput.fill('http://example.com/image.jpg');
    await urlInput.blur();

    // Should show error alert
    page.once('dialog', dialog => {
      expect(dialog.message()).toContain('URL tidak diizinkan');
      dialog.accept();
    });

    await page.waitForTimeout(500);
  });

  test('security: URL field should clear after validation error (Issue #5724)', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/premium/issues/5724 - Clear Invalid URL',
    },
  }, async ({ page }) => {
    // Open image dialog
    const imageButton = page.locator('button[data-mce-name="image"]');
    await imageButton.click();
    await page.waitForSelector('.tox-dialog');

    // Find the URL input field
    const urlInput = page.locator('.tox-dialog input[type="url"]');
    
    // Input invalid URL
    await urlInput.fill('http://127.0.0.1/image.jpg');
    
    // Handle alert
    page.once('dialog', dialog => {
      dialog.accept();
    });
    
    await urlInput.blur();
    await page.waitForTimeout(500);

    // URL field should be empty after validation error
    const fieldValue = await urlInput.inputValue();
    expect(fieldValue).toBe('');
  });
});
