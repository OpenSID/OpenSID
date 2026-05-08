import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Tinjau PDF template surat dinas error #11143', () => {
  test('fix: tinjau pdf surat dinas tidak error', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11143',
    },
  }, async ({ page }) => {
    // Pergi ke form surat dinas dengan ID 1
    await page.goto('surat_dinas/form/1');
    await page.waitForSelector('form', { timeout: 10000 });

    // Klik tombol "Tinjau PDF"
    await page.click('button:has-text("Tinjau PDF")');
    
    // Wait untuk modal atau response
    await page.waitForTimeout(2000);

    // Verify tidak ada modal error
    // Modal error biasanya memiliki class alert-danger atau modal-danger
    const hasErrorModal = await page.locator('.alert-danger, .modal-danger, [role="alert"].error').count();
    expect(hasErrorModal).toBe(0);

    // Verify tidak ada console error
    const consoleErrors: string[] = [];
    page.on('console', msg => {
      if (msg.type() === 'error') {
        consoleErrors.push(msg.text());
      }
    });

    // Verify page tidak ada error
    page.on('pageerror', err => {
      consoleErrors.push(err.message);
    });

    // Assert: Tidak ada error
    expect(consoleErrors.length).toBe(0);
  });
});
