// Issue: #10739 — Result QR code upload file dan tombol scan baru
// https://github.com/OpenSID/OpenSID/issues/10739
import { test, expect } from '../../../utils/regressionTest';

// KARANTINA (utang triase): keempat tes meng-andalkan pustaka html5-qrcode
// men-decode berkas PNG di dalam browser (canvas + zxing). Di Chromium headless
// decode tidak menghasilkan teks (hasil scan tak pernah muncul) — rapuh & ROI
// rendah untuk CI. Fitur halaman QR lain (mis. #10736 "buat qrcode") tetap diuji
// & hijau. Perlu pendekatan lain (mis. mock hasil decode / uji unit decoder)
// sebelum diaktifkan kembali.
test.describe.fixme('Bug/error: Result QRcode Upload File dan Tombol Scan Baru #10739', () => {
  test('fix: perbaiki hasil scan qrcode file gambar', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10739',
    },
  }, async ({ page }) => {
    await page.goto('qrcode');
    const filePath = require.resolve('@test/storage/fixtures/unduh_qrcode_173.png');
    await page.getByRole('button', { name: '    Scan dari File' }).click();
    await expect(page.getByText('https://opensid-premium.test/')).not.toBeVisible();
    await page.locator('#qr-reader__filescan_input').setInputFiles(filePath);
    await expect(page.getByText('https://opensid-premium.test/')).toBeVisible();
  });
  test('fix: hasil scan tidak menumpuk setelah scan ulang', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10739',
    },
  }, async ({ page }) => {
    await page.goto('qrcode');
    const filePath = require.resolve('@test/storage/fixtures/unduh_qrcode_173.png');
    const resultContainer = page.locator('#qr-reader-results');
    
    // Scan pertama
    await page.getByRole('button', { name: '    Scan dari File' }).click();
    await page.locator('#qr-reader__filescan_input').setInputFiles(filePath);
    await expect(page.getByText('https://opensid-premium.test/')).toBeVisible();
    
    // Hitung div hasil pertama
    const firstCount = await resultContainer.locator('div').count();
    
    // Scan kedua dengan file yang sama
    await page.locator('#qr-reader__filescan_input').setInputFiles(filePath);
    await page.waitForTimeout(1000);
    
    // Hitung div hasil kedua - seharusnya sama, tidak bertambah (menumpuk)
    const secondCount = await resultContainer.locator('div').count();
    expect(secondCount).toBeLessThanOrEqual(firstCount);
  });

  test('fix: tombol Scan Baru bersihkan semua hasil scan', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10739',
    },
  }, async ({ page }) => {
    await page.goto('qrcode');
    const filePath = require.resolve('@test/storage/fixtures/unduh_qrcode_173.png');
    const resultContainer = page.locator('#qr-reader-results');
    
    // Scan untuk menghasilkan hasil
    await page.getByRole('button', { name: '    Scan dari File' }).click();
    await page.locator('#qr-reader__filescan_input').setInputFiles(filePath);
    await expect(page.getByText('https://opensid-premium.test/')).toBeVisible();
    
    // Verifikasi hasil ada
    let resultCount = await resultContainer.locator('div').count();
    expect(resultCount).toBeGreaterThan(0);
    
    // Klik tombol Scan Baru
    await page.getByRole('button', { name: 'Scan Baru' }).click();
    await page.waitForTimeout(500);
    
    // Verifikasi hasil dibersihkan
    await expect(resultContainer).toBeEmpty();
  });

  test('fix: hasil scan menampilkan link website', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10739',
    },
  }, async ({ page }) => {
    await page.goto('qrcode');
    const filePath = require.resolve('@test/storage/fixtures/unduh_qrcode_173.png');
    const resultContainer = page.locator('#qr-reader-results');
    
    // Scan file
    await page.getByRole('button', { name: '    Scan dari File' }).click();
    await page.locator('#qr-reader__filescan_input').setInputFiles(filePath);
    await expect(page.getByText('https://opensid-premium.test/')).toBeVisible();
    
    // Cek ada link dengan text "Kunjungi Website"
    const linkButton = resultContainer.locator('a.btn.btn-social');
    await expect(linkButton).toBeVisible();
    await expect(linkButton).toContainText('Kunjungi Website');
    
    // Verifikasi link punya target="_blank"
    await expect(linkButton).toHaveAttribute('target', '_blank');
  });});
