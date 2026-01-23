import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

/**
 * Test untuk Issue #10753: QR Code tidak muncul pada surat dinas
 * 
 * Bug: QR Code pada surat dinas tidak muncul saat tinjau maupun hasil cetak
 * Test ini memverifikasi bahwa placeholder [qr_code] tetap ada di konten editor
 */

test.describe('Issue #10753 - QR Code Surat Dinas', () => {
  test('Placeholder [qr_code] harus tetap ada di konten editor surat', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10753',
    },
  }, async ({ page }) => {
    // Navigasi ke halaman cetak surat dinas
    await page.goto('/surat_dinas_cetak');
    
    // Tunggu tabel surat muncul
    await page.waitForSelector('table tbody tr');
    
    // Klik tombol "Buat Surat" pada baris pertama
    await page.click('table tbody tr:first-child a:has-text("Buat Surat")');
    
    // Tunggu halaman form surat terbuka
    await page.waitForLoadState('networkidle');
    
    // Isi form nomor surat
    await page.fill('input[name="nomor_surat"]', '001');
    
    // Tunggu TinyMCE editor ready
    await page.waitForSelector('iframe.tox-edit-area__iframe');
    
    // Akses iframe TinyMCE untuk melihat konten
    const editorFrame = page.frameLocator('iframe.tox-edit-area__iframe');
    
    // Ambil konten dari editor
    const editorContent = await editorFrame.locator('body').textContent();
    
    // Assert: Verifikasi bahwa [qr_code] placeholder ada di konten editor
    expect(editorContent).toContain('[qr_code]');
  });
});
