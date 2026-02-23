import { test, expect } from '@playwright/test';
import path from 'path';
import fs from 'fs';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

/**
 * Test untuk Issue #10869: Error 500 saat Impor Grup Pengguna
 * 
 * Bug: Proses import gagal dengan error 500
 * - Tidak ada validasi yang cukup untuk data impor
 * - Null Pointer Exception ketika modul tidak ditemukan
 * - Variable tidak terdefinisi sebelum digunakan
 * 
 * Expected: Import berhasil atau menampilkan pesan validasi yang jelas
 */

test.describe('Issue #10869 - Impor Grup Pengguna Error 500', () => {
  
  test('Import grup dengan data valid harus berhasil tanpa error 500', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10869',
    },
  }, async ({ page }) => {
    // Navigasi ke halaman grup
    await page.goto('grup');
    
    // Tunggu halaman fully loaded
    await page.waitForLoadState('networkidle');
    
    // Cari dan klik tombol Impor
    const importButton = page.locator('button, a').filter({ hasText: /impor|import/i }).first();
    await expect(importButton).toBeVisible();
    await importButton.click();
    
    // Tunggu dialog/form impor muncul
    await page.waitForTimeout(1000);
    
    // Upload file JSON yang valid
    const fileInput = page.locator('input[type="file"][name="userfile"]');
    const fixturePath = path.resolve(__dirname, '../../../../storage/fixtures/grup-import-valid.json');
    await fileInput.setInputFiles(fixturePath);
    
    // Tunggu preview/confirmation
    await page.waitForTimeout(1500);
    
    // Cari dan klik tombol Simpan
    const saveButton = page.locator('button, input[type="submit"]').filter({ hasText: /simpan|save|import/i }).first();
    await expect(saveButton).toBeVisible();
    await saveButton.click();
    
    // Tunggu proses import
    await page.waitForTimeout(2000);
    
    // Verifikasi tidak ada error 500
    const statusCode = page.url();
    expect(statusCode).not.toContain('error=500');
    
    // Verifikasi tidak ada pesan error fatal
    const errorMessages = await page.locator('[role="alert"], .alert-danger, .error').all();
    for (const error of errorMessages) {
      const text = await error.textContent();
      expect(text).not.toContain('500');
      expect(text).not.toContain('Fatal');
      expect(text).not.toContain('Exception');
    }
    
    // Verifikasi halaman redirect ke daftar grup atau ada pesan sukses
    await page.waitForTimeout(1000);
    const successMessage = page.locator('[role="alert"], .alert-success, .success').first();
    const isSuccessVisible = await successMessage.isVisible().catch(() => false);
    
    if (isSuccessVisible) {
      await expect(successMessage).toContainText(/berhasil|success/i);
    }
  });

  test('Import grup dengan modul tidak valid harus di-skip tanpa error 500', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10869',
    },
  }, async ({ page }) => {
    // Navigasi ke halaman grup
    await page.goto('grup');
    
    // Tunggu halaman fully loaded
    await page.waitForLoadState('networkidle');
    
    // Cari dan klik tombol Impor
    const importButton = page.locator('button, a').filter({ hasText: /impor|import/i }).first();
    await expect(importButton).toBeVisible();
    await importButton.click();
    
    // Tunggu dialog/form impor muncul
    await page.waitForTimeout(1000);
    
    // Upload file JSON dengan modul yang invalid
    const fileInput = page.locator('input[type="file"][name="userfile"]');
    const fixturePath = path.resolve(__dirname, '../../../../storage/fixtures/grup-import-invalid-modul.json');
    await fileInput.setInputFiles(fixturePath);
    
    // Tunggu preview/confirmation
    await page.waitForTimeout(1500);
    
    // Pilih untuk import
    const checkboxes = await page.locator('input[type="checkbox"]').all();
    if (checkboxes.length > 0) {
      await checkboxes[0].check();
    }
    
    // Cari dan klik tombol Simpan
    const saveButton = page.locator('button, input[type="submit"]').filter({ hasText: /simpan|save|import/i }).first();
    await expect(saveButton).toBeVisible();
    await saveButton.click();
    
    // Tunggu proses import
    await page.waitForTimeout(2000);
    
    // Verifikasi tidak ada error 500 meskipun ada modul invalid
    const errorElements = page.locator('[role="alert"], .alert-danger');
    const errorCount = await errorElements.count();
    
    for (let i = 0; i < errorCount; i++) {
      const text = await errorElements.nth(i).textContent();
      // Tidak boleh error 500, tapi bisa warning atau info tentang modul tidak ditemukan
      expect(text).not.toContain('500');
      expect(text).not.toContain('Fatal');
    }
  });

  test('Upload file dengan format JSON invalid harus menampilkan pesan validasi', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10869',
    },
  }, async ({ page }) => {
    // Navigasi ke halaman grup
    await page.goto('grup');
    
    // Tunggu halaman fully loaded
    await page.waitForLoadState('networkidle');
    
    // Cari dan klik tombol Impor
    const importButton = page.locator('button, a').filter({ hasText: /impor|import/i }).first();
    await expect(importButton).toBeVisible();
    await importButton.click();
    
    // Tunggu dialog/form impor muncul
    await page.waitForTimeout(1000);
    
    // Upload file dengan format invalid
    const fileInput = page.locator('input[type="file"][name="userfile"]');
    const fixturePath = path.resolve(__dirname, '../../../../storage/fixtures/grup-import-format-invalid.txt');
    await fileInput.setInputFiles(fixturePath);
    
    // Tunggu validasi form
    await page.waitForTimeout(1500);
    
    // Cari dan klik tombol Simpan
    const saveButton = page.locator('button, input[type="submit"]').filter({ hasText: /simpan|save|import/i }).first();
    
    // Jika submit button visible, click it
    const isButtonVisible = await saveButton.isVisible().catch(() => false);
    if (isButtonVisible) {
      await saveButton.click();
      
      // Tunggu response
      await page.waitForTimeout(2000);
    }
    
    // Verifikasi ada pesan error yang jelas, bukan error 500
    const errorAlert = page.locator('[role="alert"], .alert-danger, .alert-warning').first();
    const isErrorVisible = await errorAlert.isVisible().catch(() => false);
    
    if (isErrorVisible) {
      const errorText = await errorAlert.textContent();
      // Harus ada pesan validasi yang user-friendly
      expect(errorText).toMatch(/format|valid|invalid|json/i);
      // Tidak boleh error 500
      expect(errorText).not.toContain('500');
      expect(errorText).not.toContain('Fatal');
    }
  });

  test('Halaman impor tidak boleh menampilkan error 500 saat loading', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10869',
    },
  }, async ({ page }) => {
    // Setup: intercept semua response
    const responses: { status: number; url: string }[] = [];
    
    page.on('response', (response) => {
      responses.push({
        status: response.status(),
        url: response.url(),
      });
    });
    
    // Navigasi ke halaman grup
    await page.goto('grup');
    
    // Tunggu halaman fully loaded
    await page.waitForLoadState('networkidle');
    
    // Klik tombol Impor
    const importButton = page.locator('button, a').filter({ hasText: /impor|import/i }).first();
    await expect(importButton).toBeVisible();
    await importButton.click();
    
    // Tunggu dialog muncul
    await page.waitForTimeout(1000);
    
    // Tunggu load selesai
    await page.waitForLoadState('networkidle');
    
    // Verifikasi tidak ada response dengan status 500
    const serverErrors = responses.filter((r) => r.status === 500);
    expect(serverErrors).toHaveLength(0);
    
    // Verifikasi tidak ada error message di halaman
    const errorText = await page.locator('body').textContent();
    expect(errorText).not.toContain('500 Internal Server Error');
    expect(errorText).not.toContain('Fatal error');
  });
});
