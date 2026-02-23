import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Security: TinyMCE Media Plugin - Blind SSRF Prevention (Issue #5982)', () => {
  const testPage = 'http://opensid-premium.test/web/form/agenda';

  test.beforeEach(async ({ page }) => {
    // Navigate to a page with TinyMCE editor
    await page.goto(testPage);
    
    // Wait for TinyMCE to load
    await page.waitForSelector('[data-mce-name="media"]', { timeout: 10000 });
  });

  test('security: Media insert button should open image dialog (Issue #5982)', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/premium/issues/5982 - Blind SSRF in TinyMCE Media Plugin',
    },
  }, async ({ page }) => {
    // Find and click the image button
    const imageButton = page.locator('button[data-mce-name="media"]');
    await expect(imageButton).toBeVisible();
    await imageButton.click();

    // Modal should open
    await page.waitForSelector('.tox-dialog', { timeout: 5000 });
    const dialog = page.locator('.tox-dialog');
    await expect(dialog).toBeVisible();
  });

  test('security: Should reject localhost URL (127.0.0.1) with error message (Issue #5982)', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/premium/issues/5982 - Blind SSRF Prevention',
    },
  }, async ({ page }) => {
    // Open image dialog
    const imageButton = page.locator('button[data-mce-name="media"]');
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

  test('security: Should reject private IP addresses (192.168.x.x) (Issue #5982)', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/premium/issues/5982 - Private IP Blocking',
    },
  }, async ({ page }) => {
    // Open image dialog
    const imageButton = page.locator('button[data-mce-name="media"]');
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

  test('security: Should reject 10.x.x.x internal networks (Issue #5982)', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/premium/issues/5982 - RFC1918 Private IP Blocking',
    },
  }, async ({ page }) => {
    // Open image dialog
    const imageButton = page.locator('button[data-mce-name="media"]');
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

  test('security: Should reject AWS metadata endpoint (169.254.169.254) (Issue #5982)', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/premium/issues/5982 - AWS Metadata Protection',
    },
  }, async ({ page }) => {
    // Open image dialog
    const imageButton = page.locator('button[data-mce-name="media"]');
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

  test('security: Should reject file:// protocol URLs (Issue #5982)', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/premium/issues/5982 - Dangerous Protocol Blocking',
    },
  }, async ({ page }) => {
    // Open image dialog
    const imageButton = page.locator('button[data-mce-name="media"]');
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

  test('security: Should reject gopher:// protocol (Issue #5982)', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/premium/issues/5982 - Dangerous Protocol Blocking',
    },
  }, async ({ page }) => {
    // Open image dialog
    const imageButton = page.locator('button[data-mce-name="media"]');
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

  test('security: Should accept HTTPS URLs from whitelisted CDN domains (Issue #5982)', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/premium/issues/5982 - Whitelist CDN Domains',
    },
  }, async ({ page }) => {
    // Open image dialog
    const imageButton = page.locator('button[data-mce-name="media"]');
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

  test('security: Should accept Cloudinary images (Issue #5982)', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/premium/issues/5982 - Whitelist Cloudinary',
    },
  }, async ({ page }) => {
    // Open image dialog
    const imageButton = page.locator('button[data-mce-name="media"]');
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

  test('security: Should accept Google Drive images (Issue #5982)', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/premium/issues/5982 - Whitelist Google Drive',
    },
  }, async ({ page }) => {
    // Open image dialog
    const imageButton = page.locator('button[data-mce-name="media"]');
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

  test('security: Should accept data: URLs (Issue #5982)', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/premium/issues/5982 - Allow Data URLs',
    },
  }, async ({ page }) => {
    // Open image dialog
    const imageButton = page.locator('button[data-mce-name="media"]');
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

  test('security: Should reject unknown domains not in whitelist (Issue #5982)', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/premium/issues/5982 - Domain Whitelist Enforcement',
    },
  }, async ({ page }) => {
    // Open image dialog
    const imageButton = page.locator('button[data-mce-name="media"]');
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

  test('security: Should reject reserved hostnames like example.com (Issue #5982)', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/premium/issues/5982 - Reserved Hostname Blocking',
    },
  }, async ({ page }) => {
    // Open image dialog
    const imageButton = page.locator('button[data-mce-name="media"]');
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

  test('security: URL field should clear after validation error (Issue #5982)', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/premium/issues/5982 - Clear Invalid URL',
    },
  }, async ({ page }) => {
    // Open image dialog
    const imageButton = page.locator('button[data-mce-name="media"]');
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

  // ============================================================
  // NEW TESTS: SSRF Bypass Scenarios - Testing Edge Cases
  // ============================================================

  test('EXPLOIT: Should reject URL without protocol - imgur.com/image.jpg (Issue #5982 Bypass)', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/premium/issues/5982 - URL Parsing Bypass without protocol',
    },
  }, async ({ page }) => {
    // Open image dialog
    const imageButton = page.locator('button[data-mce-name="media"]');
    await imageButton.click();
    await page.waitForSelector('.tox-dialog');

    // Find the URL input field
    const urlInput = page.locator('.tox-dialog input[type="url"]');
    
    // Try to input URL without protocol
    // This is vulnerable because new URL('imgur.com/image.jpg', base) 
    // will be treated as relative path to current page
    await urlInput.fill('imgur.com/abc123.jpg');
    
    // Trigger validation by moving focus
    await urlInput.blur();
    
    // Should show error alert or clear the field
    page.once('dialog', dialog => {
      expect(dialog.message()).toContain('harus dimulai dengan');
      dialog.accept();
    });

    // Wait for validation to trigger
    await page.waitForTimeout(500);

    // Check if URL field was cleared
    const fieldValue = await urlInput.inputValue();
    expect(fieldValue).toBe('');
  });

  test('EXPLOIT: Should reject double protocol - http://https://malicious.com (Issue #5982 Bypass)', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/premium/issues/5982 - Double Protocol Bypass',
    },
  }, async ({ page }) => {
    // Open image dialog
    const imageButton = page.locator('button[data-mce-name="media"]');
    await imageButton.click();
    await page.waitForSelector('.tox-dialog');

    // Find the URL input field
    const urlInput = page.locator('.tox-dialog input[type="url"]');
    
    // Try to input malformed double protocol URL
    // This might bypass validation due to URL parsing behavior
    await urlInput.fill('http://https://malicious.com/image.jpg');
    await urlInput.blur();

    // Should show error alert
    page.once('dialog', dialog => {
      expect(dialog.message()).toContain('URL tidak diizinkan');
      dialog.accept();
    });

    await page.waitForTimeout(500);

    // Field should be cleared or contain valid URL
    const fieldValue = await urlInput.inputValue();
    expect(fieldValue).not.toBe('http://https://malicious.com/image.jpg');
  });

  test('EXPLOIT: Should reject @ technique bypass - imgur.com@attacker.com (Issue #5982 Bypass)', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/premium/issues/5982 - @ Symbol Bypass Technique',
    },
  }, async ({ page }) => {
    // Open image dialog
    const imageButton = page.locator('button[data-mce-name="media"]');
    await imageButton.click();
    await page.waitForSelector('.tox-dialog');

    // Find the URL input field
    const urlInput = page.locator('.tox-dialog input[type="url"]');
    
    // Try @ technique where attacker domain looks like port/path
    // https://imgur.com@attacker.com/image.jpg
    // URL parser treats @attacker.com as part of credentials, not domain
    await urlInput.fill('https://imgur.com@attacker.com/image.jpg');
    await urlInput.blur();

    // Should show error alert
    page.once('dialog', dialog => {
      expect(dialog.message()).toContain('URL tidak diizinkan');
      dialog.accept();
    });

    await page.waitForTimeout(500);

    // Field should be cleared
    const fieldValue = await urlInput.inputValue();
    expect(fieldValue).not.toBe('https://imgur.com@attacker.com/image.jpg');
  });

  test('EXPLOIT: Should reject protocol-relative URL - //malicious.com (Issue #5982 Bypass)', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/premium/issues/5982 - Protocol-Relative URL Bypass',
    },
  }, async ({ page }) => {
    // Open image dialog
    const imageButton = page.locator('button[data-mce-name="media"]');
    await imageButton.click();
    await page.waitForSelector('.tox-dialog');

    // Find the URL input field
    const urlInput = page.locator('.tox-dialog input[type="url"]');
    
    // Protocol-relative URL uses current page protocol
    // //malicious.com will become http://malicious.com (same protocol as current page)
    await urlInput.fill('//malicious.com/image.jpg');
    await urlInput.blur();

    // Should show error alert if domain not whitelisted
    page.once('dialog', dialog => {
      expect(dialog.message()).toContain('URL tidak diizinkan');
      dialog.accept();
    });

    await page.waitForTimeout(500);

    // Field should be cleared
    const fieldValue = await urlInput.inputValue();
    expect(fieldValue).not.toBe('//malicious.com/image.jpg');
  });

  test('EXPLOIT: Should reject mixed case protocol to bypass lowercase check (Issue #5982 Bypass)', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/premium/issues/5982 - Case Bypass Technique',
    },
  }, async ({ page }) => {
    // Open image dialog
    const imageButton = page.locator('button[data-mce-name="media"]');
    await imageButton.click();
    await page.waitForSelector('.tox-dialog');

    // Find the URL input field
    const urlInput = page.locator('.tox-dialog input[type="url"]');
    
    // Some validation might not normalize case properly
    // Try mixed case to bypass checks
    await urlInput.fill('HtTp://127.0.0.1/image.jpg');
    await urlInput.blur();

    // Should show error alert for localhost
    page.once('dialog', dialog => {
      expect(dialog.message()).toContain('URL tidak diizinkan');
      dialog.accept();
    });

    await page.waitForTimeout(500);
  });

  test('EXPLOIT: Should reject URL with encoded characters - http://127%2e0%2e0%2e1 (Issue #5982 Bypass)', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/premium/issues/5982 - URL Encoding Bypass',
    },
  }, async ({ page }) => {
    // Open image dialog
    const imageButton = page.locator('button[data-mce-name="media"]');
    await imageButton.click();
    await page.waitForSelector('.tox-dialog');

    // Find the URL input field
    const urlInput = page.locator('.tox-dialog input[type="url"]');
    
    // Try to encode IP address to bypass regex patterns
    // %2e = . (dot), %3a = :
    // This might bypass simple regex patterns that expect literal dots
    await urlInput.fill('http://127%2e0%2e0%2e1/image.jpg');
    await urlInput.blur();

    // Should show error alert
    page.once('dialog', dialog => {
      expect(dialog.message()).toContain('URL tidak diizinkan');
      dialog.accept();
    });

    await page.waitForTimeout(500);
  });

  test('EXPLOIT: Should reject IPv6 localhost - http://[::1]/image.jpg (Issue #5982)', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/premium/issues/5982 - IPv6 Localhost Bypass',
    },
  }, async ({ page }) => {
    // Open image dialog
    const imageButton = page.locator('button[data-mce-name="media"]');
    await imageButton.click();
    await page.waitForSelector('.tox-dialog');

    // Find the URL input field
    const urlInput = page.locator('.tox-dialog input[type="url"]');
    
    // IPv6 localhost format should also be blocked
    await urlInput.fill('http://[::1]/image.jpg');
    await urlInput.blur();

    // Should show error alert
    page.once('dialog', dialog => {
      expect(dialog.message()).toContain('URL tidak diizinkan');
      dialog.accept();
    });

    await page.waitForTimeout(500);
  });

  test('EXPLOIT: Should reject octal IP notation - http://0177.0.0.1 (Issue #5982)', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/premium/issues/5982 - Octal IP Bypass',
    },
  }, async ({ page }) => {
    // Open image dialog
    const imageButton = page.locator('button[data-mce-name="media"]');
    await imageButton.click();
    await page.waitForSelector('.tox-dialog');

    // Find the URL input field
    const urlInput = page.locator('.tox-dialog input[type="url"]');
    
    // Octal notation for IP: 0177.0.0.1 = 127.0.0.1
    // Some parsers might not catch this
    await urlInput.fill('http://0177.0.0.1/image.jpg');
    await urlInput.blur();

    // Should show error alert
    page.once('dialog', dialog => {
      expect(dialog.message()).toContain('URL tidak diizinkan');
      dialog.accept();
    });

    await page.waitForTimeout(500);
  });

  test('EXPLOIT: Should reject OOB domain (Interactsh) without protocol - pixgynseoykkounnlrstf1hr0hzrcvau4.oast.fun/test.jpg', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/premium/issues/5982 - SSRF via OOB Domain without protocol',
    },
  }, async ({ page }) => {
    // Open image dialog
    const imageButton = page.locator('button[data-mce-name="media"]');
    await imageButton.click();
    await page.waitForSelector('.tox-dialog');

    // Find the URL input field
    const urlInput = page.locator('.tox-dialog input[type="url"]');
    
    // Test #23: OOB domain WITHOUT protocol (should be rejected by layer 1)
    await urlInput.fill('pixgynseoykkounnlrstf1hr0hzrcvau4.oast.fun/test.jpg');
    await urlInput.blur();

    // Should show error alert for missing protocol
    page.once('dialog', dialog => {
      expect(dialog.message()).toContain('harus dimulai dengan');
      dialog.accept();
    });

    await page.waitForTimeout(500);

    // Verify URL field is cleared
    const fieldValue = await urlInput.inputValue();
    expect(fieldValue).toBe('');
  });

  test('EXPLOIT: Should reject OOB domain (Interactsh) with https protocol - https://pixgynseoykkounnlrstf1hr0hzrcvau4.oast.fun/test.jpg', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/premium/issues/5982 - SSRF via OOB Domain (not whitelisted)',
    },
  }, async ({ page }) => {
    // Open image dialog
    const imageButton = page.locator('button[data-mce-name="media"]');
    await imageButton.click();
    await page.waitForSelector('.tox-dialog');

    // Find the URL input field
    const urlInput = page.locator('.tox-dialog input[type="url"]');
    
    // Test #24: OOB domain with HTTPS (should be rejected by layer 2 - not in whitelist)
    // This proves that even with protocol, untrusted domains are blocked
    await urlInput.fill('https://pixgynseoykkounnlrstf1hr0hzrcvau4.oast.fun/test.jpg');
    await urlInput.blur();

    // Should show error alert for untrusted domain
    page.once('dialog', dialog => {
      expect(dialog.message()).toContain('URL tidak diizinkan');
      dialog.accept();
    });

    await page.waitForTimeout(500);

    // Verify URL field is cleared
    const fieldValue = await urlInput.inputValue();
    expect(fieldValue).toBe('');
  });

  test('EXPLOIT: Should reject OOB domain (Interactsh) with protocol-relative URL - //pixgynseoykkounnlrstf1hr0hzrcvau4.oast.fun/test.jpg', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/premium/issues/5982 - SSRF via Protocol-relative URL',
    },
  }, async ({ page }) => {
    // Open image dialog
    const imageButton = page.locator('button[data-mce-name="media"]');
    await imageButton.click();
    await page.waitForSelector('.tox-dialog');

    // Find the URL input field
    const urlInput = page.locator('.tox-dialog input[type="url"]');
    
    // Test #25: Protocol-relative URL (should be rejected by layer 2 - not in whitelist)
    // Protocol-relative URLs inherit scheme from current page (could be HTTP)
    await urlInput.fill('//pixgynseoykkounnlrstf1hr0hzrcvau4.oast.fun/test.jpg');
    await urlInput.blur();

    // Should show error alert for untrusted domain
    page.once('dialog', dialog => {
      expect(dialog.message()).toContain('URL tidak diizinkan');
      dialog.accept();
    });

    await page.waitForTimeout(500);

    // Verify URL field is cleared
    const fieldValue = await urlInput.inputValue();
    expect(fieldValue).toBe('');
  });
});
