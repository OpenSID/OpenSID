import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Navigasi halaman tidak sinkron dengan jumlah data kosong pada Buku Inventaris dan Kekayaan Desa #11134', () => {
  test('fix: paginasi hanya menampilkan halaman 1 atau disembunyikan ketika data kosong', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11134',
    },
  }, async ({ page }) => {
    // Navigate to the Buku Inventaris dan Kekayaan Desa page
    // Menu path: Buku Administrasi Desa > Administrasi Umum > Buku Inventaris dan Kekayaan Desa
    await page.goto('bumindes/inventaris_kekayaan');
    
    // Wait for page to load
    await page.waitForLoadState('networkidle');
    
    // Wait for DataTable to initialize
    await expect(page.locator('table.table')).toBeVisible();
    await page.waitForTimeout(1000);

    // Get current year for selection
    const currentYear = new Date().getFullYear();
    
    // Select a year from dropdown (if exists and different years have different data)
    const yearSelect = page.locator('select[name="tahun"], input[name="tahun"]');
    if (await yearSelect.isVisible()) {
      // Try to select a year that's likely to have no data
      await yearSelect.selectOption({ value: '2000' });
      await page.waitForTimeout(1000);
    }

    // Check if table has data
    const noDataRow = page.locator('td:has-text("Tidak ada data yang tersedia pada tabel ini")');
    const isDataEmpty = await noDataRow.isVisible().catch(() => false);

    if (isDataEmpty) {
      // Verify info text shows 0 entries
      const infoText = page.locator('.dataTables_info');
      await expect(infoText).toContainText('0 sampai 0 dari 0');

      // Check pagination element
      const paginationContainer = page.locator('.dataTables_paginate');
      const paginationButtons = paginationContainer.locator('.pagination li');
      
      // Get all pagination buttons
      const buttonCount = await paginationButtons.count();

      // When data is empty, pagination should only show 1 disabled button
      // or pagination container should be hidden
      const isPaginationHidden = await paginationContainer.evaluate((el) => {
        const style = window.getComputedStyle(el);
        return style.display === 'none' || el.offsetParent === null;
      }).catch(() => false);

      if (!isPaginationHidden) {
        // If pagination is visible, it should only have 1 button (page 1) and it should be disabled
        await expect(paginationButtons).toHaveCount(1);
        
        // The single button should be disabled or inactive
        const singleButton = paginationButtons.nth(0);
        const isDisabled = await singleButton.evaluate((el) => {
          return el.classList.contains('disabled') || el.classList.contains('inactive');
        }).catch(() => true);
        
        expect(isDisabled).toBe(true);
      }

      // Verify "Selanjutnya" (Next) button should not be visible or should be disabled
      const nextButton = paginationContainer.locator('a:has-text("Selanjutnya"), .next');
      const isNextVisible = await nextButton.isVisible().catch(() => false);
      
      if (isNextVisible) {
        const nextParent = nextButton.locator('xpath=..');
        const isNextDisabled = await nextParent.evaluate((el) => {
          return el.classList.contains('disabled') || el.classList.contains('inactive');
        }).catch(() => true);
        
        expect(isNextDisabled).toBe(true);
      }

      // Verify no additional page numbers should be shown
      // (i.e., no buttons for pages 2, 3, 4, 5, etc.)
      const numberButtons = paginationContainer.locator('.pagination li:not(.prev):not(.next)');
      const numberButtonCount = await numberButtons.count();
      
      // Should be at most 1 (just page 1)
      expect(numberButtonCount).toBeLessThanOrEqual(1);
    } else {
      // If data exists, verify pagination works normally
      const infoText = page.locator('.dataTables_info');
      const infoContent = await infoText.textContent();
      
      // Should show proper record counts (not 0)
      expect(infoContent).not.toContain('0 sampai 0 dari 0');
    }
  });

  test('verify: respons JSON bernilai 0 untuk data kosong', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11134',
    },
  }, async ({ page }) => {
    // Listen to API responses
    let apiResponse: any = null;
    
    page.on('response', async (response) => {
      if (response.url().includes('bumindes/inventaris_kekayaan') && response.status() === 200) {
        const contentType = response.headers()['content-type'];
        if (contentType && contentType.includes('application/json')) {
          try {
            apiResponse = await response.json();
          } catch (e) {
            // Response might not be JSON
          }
        }
      }
    });

    // Navigate to page
    await page.goto('bumindes/inventaris_kekayaan');
    await page.waitForLoadState('networkidle');
    
    // Trigger AJAX request with tahun that likely has no data
    const yearSelect = page.locator('select[name="tahun"]');
    if (await yearSelect.isVisible()) {
      await yearSelect.selectOption({ value: '2000' });
      await page.waitForTimeout(1000);
    }

    // If we got an API response, verify it has correct structure
    if (apiResponse) {
      expect(apiResponse).toHaveProperty('recordsTotal');
      expect(apiResponse).toHaveProperty('recordsFiltered');
      expect(apiResponse).toHaveProperty('data');
      
      // Check if data is empty
      if (Array.isArray(apiResponse.data) && apiResponse.data.length === 0) {
        // When data is empty, recordsTotal and recordsFiltered should be 0
        expect(apiResponse.recordsTotal).toBe(0);
        expect(apiResponse.recordsFiltered).toBe(0);
        expect(apiResponse.data).toEqual([]);
      }
    }
  });
});
