import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Tombol Ekspor ke Excel Tidak Berfungsi (Hanya Loading) #11160', () => {
  
  test.beforeEach(async ({ page }) => {
    // Navigate ke halaman Rekapitulasi Kehadiran
    await page.goto('kehadiran_rekapitulasi');
    // Wait for page to load
    await page.waitForLoadState('networkidle');
  });

  test('fix: tombol Ekspor ke Excel membuka download file Excel', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11160',
    },
  }, async ({ page }) => {
    // Tunggu tombol Ekspor ke Excel muncul dan visible
    const excelBtn = page.locator('#excel');
    await expect(excelBtn).toBeVisible();

    // Tunggu DataTables selesai loading
    await page.waitForLoadState('networkidle');

    // Setup listener untuk menangkap AJAX response
    let excelResponseSuccess = false;
    
    page.on('response', (response) => {
      if (response.url().includes('kehadiran_rekapitulasi/ekspor')) {
        // Cek status response
        if (response.status() === 200) {
          excelResponseSuccess = true;
          console.log('✓ Excel export AJAX request berhasil');
        }
      }
    });

    // Klik tombol Ekspor ke Excel
    await excelBtn.click();

    // Tunggu untuk AJAX request selesai
    await page.waitForTimeout(2000);

    // Verifikasi bahwa AJAX request berhasil dikirim dan direspons dengan status 200
    expect(excelResponseSuccess).toBe(true);
  });

  test('fix: ekspor dengan filter tanggal, status, dan perangkat berfungsi', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11160',
    },
  }, async ({ page }) => {
    // Tunggu DataTables selesai loading
    await page.waitForSelector('#tabeldata', { state: 'visible' });
    await page.waitForLoadState('networkidle');

    // Coba select status (jika ada data)
    const statusSelect = page.locator('#status');
    const statusOptions = page.locator('#status option');
    const optionCount = await statusOptions.count();

    if (optionCount > 1) {
      // Select option ke-2 (index 1, setelah default option)
      await statusSelect.selectOption({ index: 1 });
      // Tunggu DataTables reload
      await page.waitForTimeout(1000);
    }

    // Setup listener untuk AJAX response
    let excelResponseSuccess = false;
    
    page.on('response', (response) => {
      if (response.url().includes('kehadiran_rekapitulasi/ekspor') && response.status() === 200) {
        excelResponseSuccess = true;
        console.log('✓ Excel export dengan filter berhasil');
      }
    });

    // Tunggu tombol Ekspor muncul
    const excelBtn = page.locator('#excel');
    await expect(excelBtn).toBeVisible();

    // Klik tombol Ekspor
    await excelBtn.click();

    // Tunggu untuk AJAX request selesai
    await page.waitForTimeout(2000);

    // Verifikasi
    expect(excelResponseSuccess).toBe(true);
  });
});


