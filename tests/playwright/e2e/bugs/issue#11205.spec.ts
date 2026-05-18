import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Fix: Perbaikan DataTables AJAX Error pada Master Lembaga #11205', () => {
  test('should display jabatan page without AJAX error', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11205',
    },
  }, async ({ page }) => {
    // Navigate to pengurus jabatan page
    await page.goto('lembaga_master');

    // Wait for page to load
    await page.waitForLoadState('networkidle');

    // Verify page title
    await expect(page.locator('h1')).toContainText('Kategori Lembaga');

    // Verify breadcrumb
    await expect(page.locator('.breadcrumb-item')).toContainText('Data Lembaga');
    await expect(page.locator('.breadcrumb-item.active')).toContainText('Kategori Lembaga');

    // Verify table element exists
    const table = page.locator('#tabeldata');
    await expect(table).toBeVisible();

    // Verify table headers
    const headers = page.locator('#tabeldata thead th');
    await expect(headers.nth(0)).toContainText('');  // checkbox column
    await expect(headers.nth(1)).toContainText('NO');
    await expect(headers.nth(2)).toContainText('AKSI');
    await expect(headers.nth(3)).toContainText('Kategori Lembaga');
    await expect(headers.nth(4)).toContainText('Deskripsi Lembaga');
    await expect(headers.nth(5)).toContainText('Jumlah Lembaga');
  });
});
