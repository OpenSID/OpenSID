import { test, expect } from '@playwright/test';

test.describe('Tampilan Daftar Layanan Mandiri Mobile #10880', () => {

  test('fix: form pendaftaran rapi di mode mobile', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10880',
    },
  }, async ({ page }) => {

    // Set viewport mobile
    await page.setViewportSize({ width: 375, height: 812 });

    // Buka halaman layanan mandiri daftar
    await page.goto('layanan-mandiri/daftar');

    // Pastikan field Nama tampil
    const namaField = page.getByPlaceholder('Nama');
    await expect(namaField).toBeVisible();

    // Cek field full width (tidak setengah kolom)
    const box = await namaField.boundingBox();
    expect(box?.width).toBeGreaterThan(300);

    // Cek input file sejajar (tidak punya margin-left)
    const fileInput = page.locator('input[type="file"]').first();
    const marginLeft = await fileInput.evaluate(el => 
      window.getComputedStyle(el).marginLeft
    );

    expect(marginLeft).toBe('0px');
  });

});