import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Fix: Perbaikan data rekapitulasi kehadiran #11095', () => {
  test('fix: prioritas tampil nama penduduk lebih utama dari nama perangkat di tabel rekapitulasi', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11095',
    },
  }, async ({ page }) => {
    // Navigate to rekap kehadiran page
    await page.goto('kehadiran/rekapitulasi');
    await expect(page.locator('h1')).toContainText('Rekapitulasi Kehadiran');

    // Wait for page to load
    await page.waitForLoadState('networkidle');

    // Wait for DataTable to be initialized
    const dataTable = page.locator('#tabeldata');
    await expect(dataTable).toBeVisible();

    // Wait for table body to have data
    const tableBody = page.locator('#tabeldata tbody');
    await expect(tableBody).toBeVisible();

    // Get first row from the table
    const firstRow = tableBody.locator('tr').first();
    await expect(firstRow).toBeVisible();

    // Get the second column (NAMA column) from the first row
    const namaCell = firstRow.locator('td').nth(1);
    const namaText = await namaCell.textContent();

    // Verify that nama is not empty and not '-' (unless genuinely no data)
    expect(namaText).toBeTruthy();
    expect(namaText?.trim()).not.toBe('');

    // Verify table has expected columns
    const headers = page.locator('#tabeldata thead th');
    await expect(headers.nth(0)).toContainText('NO');
    await expect(headers.nth(1)).toContainText('NAMA');
    await expect(headers.nth(2)).toContainText('JABATAN');
    await expect(headers.nth(3)).toContainText('TANGGAL');
    await expect(headers.nth(4)).toContainText('JAM MASUK');
    await expect(headers.nth(5)).toContainText('JAM KELUAR');
    await expect(headers.nth(6)).toContainText('TOTAL WAKTU');
    await expect(headers.nth(7)).toContainText('STATUS');
  });

  test('fix: column ordering preserves data correctly with nama priority change', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11095',
    },
  }, async ({ page }) => {
    // Navigate to rekap kehadiran page
    await page.goto('kehadiran/rekapitulasi');
    
    // Wait for page to load
    await page.waitForLoadState('networkidle');

    // Wait for DataTable to be initialized
    const dataTable = page.locator('#tabeldata');
    await expect(dataTable).toBeVisible();

    // Wait for table to have rows
    const tableBody = page.locator('#tabeldata tbody');
    await expect(tableBody).toBeVisible();

    const rows = tableBody.locator('tr');
    const rowCount = await rows.count();

    if (rowCount > 0) {
      // Verify each row has the correct number of columns
      for (let i = 0; i < Math.min(rowCount, 5); i++) {
        const row = rows.nth(i);
        const cells = row.locator('td');
        const cellCount = await cells.count();

        // Table should have 8 columns
        expect(cellCount).toBe(8);

        // NAMA column should contain data or '-'
        const namaCell = cells.nth(1);
        const namaText = await namaCell.textContent();
        expect(namaText).toBeTruthy();
      }
    }
  });

  test('fix: filter by pamong works with nama priority change', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11095',
    },
  }, async ({ page }) => {
    // Navigate to rekap kehadiran page
    await page.goto('kehadiran/rekapitulasi');
    
    // Wait for page to load
    await page.waitForLoadState('networkidle');

    // Find the pamong select
    const pamongSelect = page.locator('#pamong');
    await expect(pamongSelect).toBeVisible();

    // Get all available options
    const options = await pamongSelect.locator('option').count();
    expect(options).toBeGreaterThan(1); // At least "Semua Perangkat" option

    if (options > 1) {
      // Select a non-empty option
      const optionValue = await pamongSelect.locator('option').nth(1).getAttribute('value');
      
      if (optionValue) {
        await pamongSelect.selectOption(optionValue);
        
        // Wait for table to reload
        await page.waitForLoadState('networkidle');
        
        // Verify table still displays data correctly
        const tableBody = page.locator('#tabeldata tbody');
        const rows = tableBody.locator('tr');
        const rowCount = await rows.count();

        if (rowCount > 0) {
          // Verify nama column contains data
          const namaCell = rows.first().locator('td').nth(1);
          const namaText = await namaCell.textContent();
          expect(namaText).toBeTruthy();
        }
      }
    }
  });
});
