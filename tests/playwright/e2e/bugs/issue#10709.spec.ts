import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Fungsi tombol batal pada pengaturan surat #10709', () => {
  test('fix: perbaiki Fungsi tombol batal pada pengaturan surat', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10709',
    },
  }, async ({ page }) => {
    // Go to the create letter settings page
    await page.goto('surat_master/form');

    // --- Test "Gunakan Penomoran Surat Global" radio button ---
    const penomoranGroup = page.locator('.form-group:has(label:has-text("Gunakan Penomoran Surat Global"))');
    const penomoranYa = penomoranGroup.locator('label.btn:has-text("Ya")');
    const penomoranTidak = penomoranGroup.locator('label.btn:has-text("Tidak")');
    const penomoranYaInput = penomoranGroup.locator('input[name="format_nomor_global"][value="1"]');
    const penomoranTidakInput = penomoranGroup.locator('input[name="format_nomor_global"][value="0"]');

    // Determine initial state and test resetting from the opposite state
    if (await penomoranYaInput.isChecked()) {
      // Initially "Ya", so test changing to "Tidak" and resetting
      await penomoranTidak.click();
      await expect(penomoranTidakInput).toBeChecked();
      await page.locator('button[type="reset"]:has-text("Batal")').click();
      await expect(penomoranYaInput).toBeChecked();
    } else {
      // Initially "Tidak", so test changing to "Ya" and resetting
      await expect(penomoranTidakInput).toBeChecked();
      await penomoranYa.click();
      await expect(penomoranYaInput).toBeChecked();
      await page.locator('button[type="reset"]:has-text("Batal")').click();
      await expect(penomoranTidakInput).toBeChecked();
    }

    // --- Test "Tampilkan QR Code" radio button ---
    const qrCodeGroup = page.locator('.form-group:has(label:has-text("Tampilkan QR Code"))');
    const qrCodeYa = qrCodeGroup.locator('label.btn:has-text("Ya")');
    const qrCodeTidak = qrCodeGroup.locator('label.btn:has-text("Tidak")');
    const qrCodeYaInput = qrCodeGroup.locator('input[name="qr_code"][value="1"]');
    const qrCodeTidakInput = qrCodeGroup.locator('input[name="qr_code"][value="0"]');

    // Determine initial state and test resetting from the opposite state
    if (await qrCodeYaInput.isChecked()) {
      // Initially "Ya", so test changing to "Tidak" and resetting
      await qrCodeTidak.click();
      await expect(qrCodeTidakInput).toBeChecked();
      await page.locator('button[type="reset"]:has-text("Batal")').click();
      await expect(qrCodeYaInput).toBeChecked();
    } else {
      // Initially "Tidak", so test changing to "Ya" and resetting
      await expect(qrCodeTidakInput).toBeChecked();
      await qrCodeYa.click();
      await expect(qrCodeYaInput).toBeChecked();
      await page.locator('button[type="reset"]:has-text("Batal")').click();
      await expect(qrCodeTidakInput).toBeChecked();
    }
  });
});
