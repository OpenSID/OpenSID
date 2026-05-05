import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Fix: Perbaikan DataTables AJAX Error pada Tab Jabatan #11128', () => {
  test('should display jabatan page without AJAX error', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11128',
    },
  }, async ({ page }) => {
    // Navigate to pengurus jabatan page
    await page.goto('pengurus/jabatan');

    // Wait for page to load
    await page.waitForLoadState('networkidle');

    // Verify page title
    await expect(page.locator('h1')).toContainText('Daftar Jabatan Pengurus');

    // Verify breadcrumb
    await expect(page.locator('.breadcrumb-item')).toContainText('Pengurus');
    await expect(page.locator('.breadcrumb-item.active')).toContainText('Daftar Jabatan Pengurus');

    // Verify table element exists
    const table = page.locator('#tabeldata');
    await expect(table).toBeVisible();

    // Verify table headers
    const headers = page.locator('#tabeldata thead th');
    await expect(headers.nth(0)).toContainText('');  // checkbox column
    await expect(headers.nth(1)).toContainText('NO');
    await expect(headers.nth(2)).toContainText('AKSI');
    await expect(headers.nth(3)).toContainText('Jabatan');
  });

  test('should load datatable data via ajax correctly', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11128',
    },
  }, async ({ page }) => {
    // Navigate to pengurus jabatan page
    await page.goto('pengurus/jabatan');

    // Wait for page to load
    await page.waitForLoadState('networkidle');

    // Wait for DataTable to be initialized
    const table = page.locator('#tabeldata');
    await expect(table).toBeVisible();

    // Wait for table body to have data
    const tableBody = page.locator('#tabeldata tbody');
    await expect(tableBody).toBeVisible();

    // Wait for at least one row to appear
    const rows = tableBody.locator('tr');
    const rowCount = await rows.count();
    
    // If there are rows, verify they are not error rows
    if (rowCount > 0) {
      const firstRow = rows.first();
      await expect(firstRow).toBeVisible();

      // Verify that the row has the expected columns
      const cells = firstRow.locator('td');
      const cellCount = await cells.count();
      expect(cellCount).toBeGreaterThanOrEqual(3); // checkbox, action, nama
    }
  });

  test('should separate view and datatables routes', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11128',
    },
  }, async ({ page, context }) => {
    // Intercept network requests
    let ajaxUrl = '';
    
    page.on('response', (response) => {
      if (response.url().includes('jabatan') && response.request().method() === 'POST') {
        ajaxUrl = response.url();
      }
    });

    // Navigate to pengurus jabatan page
    await page.goto('pengurus/jabatan');

    // Wait for page to load and AJAX request to be made
    await page.waitForLoadState('networkidle');

    // Wait for table to load
    const tableBody = page.locator('#tabeldata tbody');
    await expect(tableBody).toBeVisible();

    // Verify the AJAX request was made to the correct endpoint
    expect(ajaxUrl).toMatch(/jabatan\/datatables/i);

    // Verify the page URL is the view URL (GET)
    expect(page.url()).toMatch(/pengurus\/jabatan$/i);
  });

  test('should show buttons based on permissions', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11128',
    },
  }, async ({ page }) => {
    // Navigate to pengurus jabatan page
    await page.goto('pengurus/jabatan');

    // Wait for page to load
    await page.waitForLoadState('networkidle');

    // Wait for table body to have data
    const tableBody = page.locator('#tabeldata tbody');
    await expect(tableBody).toBeVisible();

    // Wait for at least one row to appear
    const rows = tableBody.locator('tr');
    const rowCount = await rows.count();

    if (rowCount > 0) {
      // Verify action buttons are rendered
      const actionCells = tableBody.locator('td:nth-child(3)'); // Action column
      const firstActionCell = actionCells.first();
      
      // Verify at least one button exists
      const buttons = firstActionCell.locator('a');
      const buttonCount = await buttons.count();
      expect(buttonCount).toBeGreaterThan(0);

      // Verify checkbox column for non-protected positions
      const checkboxes = tableBody.locator('input[type="checkbox"][name="id_cb[]"]');
      const checkboxCount = await checkboxes.count();
      
      // Should have at least some checkboxes (excluding protected positions like kades/sekdes)
      if (rowCount > 2) {
        expect(checkboxCount).toBeGreaterThan(0);
      }
    }
  });
});
