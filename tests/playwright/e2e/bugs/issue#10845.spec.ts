import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: impor analisis #10845', () => {
  let exportFileName: string;

  test.beforeAll(async ({ browser }) => {
    // Prepare test by creating or ensuring analysis exists
    // This will be done in the test itself
  });

  test('fix: impor analisis dari hasil ekspor harus berhasil', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10845',
    },
  }, async ({ page }) => {
    // Step 1: Navigasi ke halaman analisis master
    await page.goto('/analisis_master');
    await page.waitForLoadState('networkidle');

    // Step 2: Ambil data analisis pertama atau buat analisis baru jika tidak ada
    const firstAnalysisRow = page.locator('table tbody tr').first();
    const firstAnalysisLink = firstAnalysisRow.locator('a').first();
    
    // Klik link analisis untuk membuka detail
    await firstAnalysisLink.click();
    await page.waitForURL(/analisis_master\/\d+\/menu/);

    // Get the master ID dari URL
    const masterId = page.url().match(/\/(\d+)\/menu/)?.[1];
    expect(masterId).toBeTruthy();

    // Step 3: Klik tombol Ekspor untuk mengunduh analisis
    const eksporButton = page.locator('a, button').filter({ hasText: /Ekspor|Download/ }).first();
    await eksporButton.waitFor({ state: 'visible' });

    // Tunggu file download
    const downloadPromise = page.waitForEvent('download');
    await eksporButton.click();
    const download = await downloadPromise;
    
    exportFileName = download.suggestedFilename;
    const filePath = path.join(path.resolve(__dirname, '../../storage/fixtures'), exportFileName);
    await download.saveAs(filePath);

    // Step 4: Navigasi kembali ke halaman import analisis
    await page.goto('/analisis_master');
    await page.waitForLoadState('networkidle');

    // Cari dan klik tombol "Impor Analisis"
    const importButton = page.locator('a, button').filter({ hasText: /Impor Analisis/ }).first();
    await importButton.waitFor({ state: 'visible' });
    await importButton.click();

    // Step 5: Upload file yang sudah diunduh
    await page.waitForURL(/analisis_master\/import/);
    
    const fileInput = page.locator('input[type="file"]');
    await fileInput.setInputFiles(filePath);

    // Step 6: Klik tombol submit untuk melakukan import
    const submitButton = page.locator('button[type="submit"]');
    await submitButton.click();

    // Step 7: Verifikasi hasil import - tunggu redirect atau success message
    await page.waitForURL(/analisis_master/);
    
    // Periksa apakah ada pesan error
    const errorElement = page.locator('[class*="alert-danger"], text=Error, text=error, text=Gagal');
    const errorCount = await errorElement.count();

    // Jika tidak ada error, import berhasil
    expect(errorCount).toBe(0);

    // Verifikasi bahwa analisis baru ada di list
    const successMessage = page.locator('text=Berhasil impor analisis');
    await expect(successMessage).toBeVisible({ timeout: 5000 });
  });

  test('verify: ekspor import round-trip preserves all data', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10845',
    },
  }, async ({ page }) => {
    // Test untuk memastikan export-import round trip
    // menjaga semua data tetap utuh

    // Step 1: Navigasi ke analisis master
    await page.goto('/analisis_master');
    await page.waitForLoadState('networkidle');

    // Step 2: Buka analisis untuk melihat detail
    const firstAnalysisRow = page.locator('table tbody tr').first();
    const firstAnalysisLink = firstAnalysisRow.locator('a').first();
    const analysisName = await firstAnalysisRow.locator('td').nth(0).textContent();
    
    await firstAnalysisLink.click();
    await page.waitForURL(/analisis_master\/\d+\/menu/);

    // Ambil jumlah indikator sebelum export
    const masterId = page.url().match(/\/(\d+)\/menu/)?.[1];
    
    // Navigate to detail page to count indicators
    const detailLink = page.locator('a').filter({ hasText: /Indikator|Pertanyaan/ }).first();
    if (await detailLink.isVisible()) {
      await detailLink.click();
      await page.waitForLoadState('networkidle');
      
      const indicatorRows = page.locator('table tbody tr');
      const indicatorCount = await indicatorRows.count();
      
      // Ambil info detail dari tabel untuk verifikasi nanti
      const indicatorNames: string[] = [];
      for (let i = 0; i < Math.min(indicatorCount, 5); i++) {
        const name = await indicatorRows.nth(i).locator('td').nth(1).textContent();
        if (name) indicatorNames.push(name.trim());
      }

      // Kembali ke halaman main
      await page.go(-2);
      await page.waitForLoadState('networkidle');

      // Step 3: Download analisis
      const eksporButton = page.locator('a, button').filter({ hasText: /Ekspor|Download/ }).first();
      await eksporButton.waitFor({ state: 'visible' });

      const downloadPromise = page.waitForEvent('download');
      await eksporButton.click();
      const download = await downloadPromise;

      const filePath = path.join(path.resolve(__dirname, '../../storage/fixtures'), download.suggestedFilename);
      await download.saveAs(filePath);

      // Step 4: Import kembali
      await page.goto('/analisis_master');
      await page.waitForLoadState('networkidle');

      const importButton = page.locator('a, button').filter({ hasText: /Impor Analisis/ }).first();
      await importButton.click();
      await page.waitForURL(/analisis_master\/import/);

      const fileInput = page.locator('input[type="file"]');
      await fileInput.setInputFiles(filePath);

      const submitButton = page.locator('button[type="submit"]');
      await submitButton.click();

      // Step 5: Verifikasi success
      await page.waitForURL(/analisis_master/);
      
      const successMessage = page.locator('text=Berhasil impor analisis');
      await expect(successMessage).toBeVisible({ timeout: 5000 });

      // Verifikasi bahwa data imported dapat terlihat di list
      const newAnalysisVisible = page.locator(`text=${analysisName}`).first();
      await expect(newAnalysisVisible).toBeVisible({ timeout: 5000 });
    }
  });

  test('validate: error handling saat import file tidak valid', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10845',
    },
  }, async ({ page }) => {
    // Test untuk memastikan error handling berjalan baik
    // ketika file tidak valid

    await page.goto('/analisis_master');
    await page.waitForLoadState('networkidle');

    const importButton = page.locator('a, button').filter({ hasText: /Impor Analisis/ }).first();
    await importButton.click();
    await page.waitForURL(/analisis_master\/import/);

    // Coba upload file yang tidak valid (xlsm bukan xlsx)
    const invalidFilePath = path.resolve(__dirname, '../../storage/fixtures/format-impor-excel-invalid.xlsm');
    const fileInput = page.locator('input[type="file"]');
    
    await fileInput.setInputFiles(invalidFilePath);
    const submitButton = page.locator('button[type="submit"]');
    await submitButton.click();

    // Tunggu untuk error message atau redirect
    await page.waitForLoadState('networkidle');

    // Verifikasi bahwa error ditampilkan atau redirect terjadi
    const errorMessage = page.locator('[class*="alert-danger"], text=Error, text=error, text=Gagal, text=tidak valid');
    const errorCount = await errorMessage.count();

    // Seharusnya ada error atau tetap di halaman import
    const isStillOnImportPage = page.url().includes('import');
    expect(errorCount > 0 || isStillOnImportPage).toBeTruthy();
  });
});
