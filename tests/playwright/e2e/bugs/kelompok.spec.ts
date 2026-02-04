import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Kelompok Module Icons', () => {
  test.beforeEach(async ({ page }) => {
    // Navigate to kelompok module
    await page.goto('kelompok');
    
    // Wait for page to load
    await page.waitForLoadState('networkidle');
  });

  test('should display icons on tambah anggota kelompok options', async ({ page }) => {
    // Step 1: Tunggu tabel data kelompok dimuat
    await page.waitForSelector('table');
    
    // Step 2: Klik tombol rincian pada data pertama di tabel
    // Cari tombol rincian (biasanya ada di kolom aksi)
    const detailButton = page.locator('table tbody tr:first-child a:has-text("Rincian Data"), table tbody tr:first-child a[title*="Rincian Data"], table tbody tr:first-child .btn-primary');
    await expect(detailButton).toBeVisible();
    await detailButton.click();
    
    // Step 3: Tunggu halaman detail terload
    await page.waitForLoadState('networkidle');
    
    // Step 4: Klik tombol Tambah
    const tambahButton = page.locator('button:has-text("Tambah"), a:has-text("Tambah"), .btn-success:has-text("Tambah")');
    await expect(tambahButton).toBeVisible();
    await tambahButton.click();
    
    // Step 5: Tunggu dropdown/menu muncul
    await page.waitForTimeout(500);
    
    // Step 6: Cek apakah ada opsi "Tambah Satu Anggota Kelompok" dengan icon
    const tambahSatuOption = page.locator('a:has-text("Tambah Satu Anggota Kelompok"), button:has-text("Tambah Satu Anggota Kelompok")');
    await expect(tambahSatuOption).toBeVisible();
    
    // Cek apakah ada icon pada opsi pertama
    const tambahSatuIcon = tambahSatuOption.locator('i, span');
    const iconExists1 = await tambahSatuIcon.count() > 0;
    expect(iconExists1).toBe(true);
    
    // Verifikasi icon memiliki class fa (Font Awesome)
    const iconClass = await tambahSatuIcon.first().getAttribute('class');
    expect(iconClass).toMatch(/fa\s/);
    
    // Step 7: Cek apakah ada opsi "Tambah Beberapa Anggota Kelompok" dengan icon
    const tambahBeberapaOption = page.locator('a:has-text("Tambah Beberapa Anggota Kelompok"), button:has-text("Tambah Beberapa Anggota Kelompok")');
    await expect(tambahBeberapaOption).toBeVisible();
    
    // Cek apakah ada icon pada opsi kedua
    const tambahBeberapaIcon = tambahBeberapaOption.locator('i, span');
    const iconExists2 = await tambahBeberapaIcon.count() > 0;
    expect(iconExists2).toBe(true);
    
    // Verifikasi icon memiliki class fa (Font Awesome)
    const iconClass2 = await tambahBeberapaIcon.first().getAttribute('class');
    expect(iconClass2).toMatch(/fa\s/);
    
    // Test berhasil jika kedua icon ditemukan
    test.info().annotations.push({
      type: 'success',
      description: 'Icons ditemukan pada pilihan tambah anggota kelompok'
    });
  });

  test('should verify icon visibility on dropdown menu', async ({ page }) => {
    // Alternative test dengan fokus pada visibility icon
    await page.waitForSelector('table');
    
    // Click detail button
    const detailButton = page.locator('table tbody tr:first-child').locator('[class*="btn"]').first();
    await detailButton.click();
    
    await page.waitForLoadState('networkidle');
    
    // Click add button (split button atau dropdown)
    const addButton = page.locator('button:has-text("Tambah"), a:has-text("Tambah")').first();
    await addButton.click();
    
    // Wait for dropdown to appear
    await page.waitForTimeout(300);
    
    // Verify both options with icons are visible
    const options = page.locator('a[class*="dropdown-item"], a[class*="dropdown-menu"] a, .dropdown-menu a');
    const optionCount = await options.count();
    
    // Should have at least 2 options (Tambah Satu and Tambah Beberapa)
    expect(optionCount).toBeGreaterThanOrEqual(2);
    
    // Check icons in dropdown items
    for (let i = 0; i < Math.min(2, optionCount); i++) {
      const option = options.nth(i);
      const icon = option.locator('i');
      const hasIcon = await icon.count() > 0;
      expect(hasIcon).toBe(true);
      
      // Verify it's a Font Awesome icon
      if (hasIcon) {
        const iconClass = await icon.first().getAttribute('class');
        expect(iconClass).toBeTruthy();
      }
    }
  });
});
