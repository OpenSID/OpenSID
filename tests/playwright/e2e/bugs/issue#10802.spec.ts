import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: error saat import data analisis #10802', () => {
  test('fix: import data analisis responden dengan csrf token', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10802',
    },
  }, async ({ page }) => {
    // Step 1: Navigasi ke halaman analisis master
    await page.goto('analisis_master');
    
    // Step 2: Klik pada menu salah satu analisis (data dasar keluarga prodeskel)
    // Mengasumsikan ada minimal satu analisis yang sudah ada
    await page.getByRole('row').first().locator('a').first().click();
    
    // Step 3: Tunggu halaman menu analisis dimuat
    await page.waitForURL(/analisis_master\/\d+\/menu/);
    
    // Step 4: Klik pada tab Data Sensus/Survei untuk melihat responden
    const responderLink = page.locator('a').filter({ hasText: /Data Sensus|Responden/i }).first();
    if (await responderLink.isVisible()) {
      await responderLink.click();
      await page.waitForURL(/analisis_respon/);
    }
    
    // Step 5: Klik tombol "Unduh Data"
    const unduhButton = page.locator('button, a').filter({ hasText: 'Unduh' }).first();
    await expect(unduhButton).toBeVisible();
    await unduhButton.click();
    
    // Step 6: Modal dialog "Unduh Data" tampil
    await expect(page.locator('text=Unduh Data')).toBeVisible();
    
    // Step 7: Klik tombol "Form Excel + Isi Data"
    const formExcelButton = page.locator('button, a').filter({ hasText: /Form Excel \+ Isi Data/i }).first();
    await expect(formExcelButton).toBeVisible();
    await formExcelButton.click();
    
    // Step 8: Tunggu download selesai
    const downloadPromise = page.waitForEvent('download');
    const download = await downloadPromise;
    
    // Step 9: Klik tombol "Tutup"
    const closeButton = page.locator('button').filter({ hasText: /Tutup|Batal|Close/i }).last();
    await expect(closeButton).toBeVisible();
    await closeButton.click();
    
    // Step 10: Modal ditutup
    await expect(page.locator('text=Unduh Data')).not.toBeVisible();
    
    // Step 11: Klik tombol "Impor"
    const importButton = page.locator('button, a').filter({ hasText: /Impor/i }).first();
    await expect(importButton).toBeVisible();
    await importButton.click();
    
    // Step 12: Modal "Impor Data" tampil
    await expect(page.locator('text=Impor Data')).toBeVisible();
    
    // Step 13: Klik tombol "Browse"
    const browseButton = page.locator('button').filter({ hasText: 'Browse' }).first();
    await expect(browseButton).toBeVisible();
    await browseButton.click();
    
    // Step 14: Dialog file chooser tampil, pilih file excel yang telah didownload
    const filePath = download.path();
    const fileInput = page.locator('input[type="file"]').first();
    await fileInput.setInputFiles(filePath!);
    
    // Step 15: Verifikasi file telah dipilih
    const filePathInput = page.locator('input[type="text"]').first();
    await expect(filePathInput).toHaveValue(/.*xlsx?$/i);
    
    // Step 16: Klik tombol "Simpan" untuk melakukan import
    const submitButton = page.locator('button').filter({ hasText: /Simpan|Impor/i }).last();
    await expect(submitButton).toBeVisible();
    await submitButton.click();
    
    // Step 17: Verifikasi bahwa data diproses dan tidak redirect ke halaman error
    // Tunggu hingga modal ditutup atau halaman berubah
    await page.waitForTimeout(2000);
    
    // Step 18: Pastikan tidak ada error message
    const errorMessages = page.locator('text=/Error|An Error Was Encountered|The action you have requested is not allowed/i');
    await expect(errorMessages).not.toBeVisible();
    
    // Step 19: Verifikasi halaman tetap di halaman responden atau berhasil diproses
    const pageUrl = page.url();
    expect(pageUrl).toMatch(/analisis_respon|analisis_master/);
  });
});
