import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: pada pencarian datatables halaman penduduk harus ada validasi input max 50 karakter #10835', () => {
  test('fix: membatasi input pencarian datatables maksimal 50 karakter', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10835',
    },
  }, async ({ page }) => {
    // 1. Masuk ke halaman penduduk
    await page.goto('penduduk');
    
    // Tunggu DataTables selesai loading
    await page.waitForSelector('[id="tabeldata"]', { timeout: 10000 });
    await page.waitForLoadState('networkidle', { timeout: 10000 });

    // 2. Cari search input di DataTables
    const searchInput = page.locator('.dataTables_filter input[type="search"]');
    
    // Pastikan search input tersedia
    await expect(searchInput).toBeVisible({ timeout: 5000 });

    // Buat text lebih dari 100 kata untuk ditest
    const longText = 'Lorem ipsum dolor sit amet consectetur adipiscing elit sed do eiusmod tempor incididunt ut labore et dolore magna aliqua Ut enim ad minim veniam quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat tes panjang banget ini lebih dari 100 kata untuk memastikan pembatasan 50 karakter berfungsi dengan baik dan tidak terjadi error pada datatables';
    
    console.log(`Text yang diinput: ${longText}`);
    console.log(`Panjang text: ${longText.length} karakter`);

    // 3. Masukkan input lebih dari 100 kata ke search input
    await searchInput.fill(longText);
    
    // Tunggu sebentar agar JavaScript validator berjalan
    await page.waitForTimeout(500);

    // 4. Cek bahwa input sudah dipotong maksimal 50 karakter
    const inputValue = await searchInput.inputValue();
    console.log(`Value setelah input: ${inputValue}`);
    console.log(`Panjang setelah input: ${inputValue.length} karakter`);
    
    // Verifikasi bahwa input tidak lebih dari 50 karakter
    expect(inputValue.length).toBeLessThanOrEqual(50);
    expect(inputValue.length).toBeGreaterThan(0); // Harus ada nilai

    // 5. Cek apakah ada warning message yang tampil
    const warningMessage = page.locator('.search-limit-warning');
    const isWarningVisible = await warningMessage.isVisible().catch(() => false);
    console.log(`Warning message visible: ${isWarningVisible}`);

    // 6. Cek apakah tidak ada error DataTables
    // Error DataTables biasanya muncul di console atau di alert
    const errorMessages = page.locator('text=DataTables warning');
    const errorVisible = await errorMessages.isVisible().catch(() => false);
    console.log(`DataTables error visible: ${errorVisible}`);
    
    expect(errorVisible).toBe(false);

    // 7. Verifikasi tidak ada JavaScript error di console
    let consoleErrors: string[] = [];
    page.on('console', msg => {
      if (msg.type() === 'error') {
        consoleErrors.push(msg.text());
      }
    });

    // Tunggu sebentar untuk menangkap console messages
    await page.waitForTimeout(1000);

    // Filter console errors yang tidak perlu
    const relevantErrors = consoleErrors.filter(error => 
      error.toLowerCase().includes('datatables') || 
      error.toLowerCase().includes('search')
    );

    console.log(`Console errors: ${relevantErrors.length}`);
    if (relevantErrors.length > 0) {
      console.log('Errors found:', relevantErrors);
    }

    expect(relevantErrors.length).toBe(0);

    // 8. Cek bahwa DataTables masih berfungsi (tidak crash)
    const tabeldata = page.locator('#tabeldata');
    await expect(tabeldata).toBeVisible();

    // 9. Cek placeholder attribute
    const placeholder = await searchInput.getAttribute('placeholder');
    console.log(`Placeholder: ${placeholder}`);
    expect(placeholder).toContain('50 karakter');

    // 10. Cek tooltip title attribute
    const title = await searchInput.getAttribute('title');
    console.log(`Title/Tooltip: ${title}`);
    expect(title).toContain('50 karakter');

    console.log('✓ Test passed: Pencarian dibatasi maksimal 50 karakter tanpa error');
  });

  test('verify: input normal tidak terpengaruh oleh validasi', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10835',
    },
  }, async ({ page }) => {
    await page.goto('penduduk');
    
    // Tunggu DataTables selesai loading
    await page.waitForSelector('[id="tabeldata"]', { timeout: 10000 });
    await page.waitForLoadState('networkidle', { timeout: 10000 });

    const searchInput = page.locator('.dataTables_filter input[type="search"]');
    await expect(searchInput).toBeVisible({ timeout: 5000 });

    // Masukkan text normal (kurang dari 50 karakter)
    const normalText = 'John Doe';
    await searchInput.fill(normalText);
    await page.waitForTimeout(500);

    const inputValue = await searchInput.inputValue();
    console.log(`Normal input value: ${inputValue}`);

    // Verifikasi input tidak berubah
    expect(inputValue).toBe(normalText);

    // Verifikasi tidak ada warning message
    const warningMessage = page.locator('.search-limit-warning');
    const isWarningVisible = await warningMessage.isVisible().catch(() => false);
    expect(isWarningVisible).toBe(false);

    console.log('✓ Test passed: Input normal tidak terpengaruh validasi');
  });

  test('verify: input exactly 50 karakter tidak ditampilkan warning', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10835',
    },
  }, async ({ page }) => {
    await page.goto('penduduk');
    
    // Tunggu DataTables selesai loading
    await page.waitForSelector('[id="tabeldata"]', { timeout: 10000 });
    await page.waitForLoadState('networkidle', { timeout: 10000 });

    const searchInput = page.locator('.dataTables_filter input[type="search"]');
    await expect(searchInput).toBeVisible({ timeout: 5000 });

    // Buat text exactly 50 karakter
    const text50Chars = 'a'.repeat(50);
    await searchInput.fill(text50Chars);
    await page.waitForTimeout(500);

    const inputValue = await searchInput.inputValue();
    console.log(`Input value length: ${inputValue.length}`);

    // Verifikasi input tidak dirubah
    expect(inputValue.length).toBe(50);
    expect(inputValue).toBe(text50Chars);

    // Verifikasi tidak ada warning message
    const warningMessage = page.locator('.search-limit-warning');
    const isWarningVisible = await warningMessage.isVisible().catch(() => false);
    expect(isWarningVisible).toBe(false);

    console.log('✓ Test passed: Input 50 karakter tidak ditampilkan warning');
  });
});
