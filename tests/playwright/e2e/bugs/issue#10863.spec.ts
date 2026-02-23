import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Stored XSS via SVG Preview & Logic Error pada Fungsi previewImage di Halaman Seting Aplikasi #10863', () => {
  test('layer-1: menolak file SVG berdasarkan MIME type', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10863',
    },
  }, async ({ page }) => {
    await page.goto('setting');

    const svgPath = require.resolve('@test/storage/fixtures/test.svg');
    await page.locator('input#file').setInputFiles(svgPath);

    // Tunggu swal2 muncul
    await expect(page.locator('.swal2-html-container')).toBeVisible();

    // Pastikan pesan error sesuai
    await expect(page.locator('.swal2-html-container')).toContainText('Format file tidak didukung');

    // Pastikan input direset setelah penolakan
    await expect(page.locator('input#file')).toHaveValue('');

    // Pastikan src img tidak berubah menjadi data URI
    await expect(page.locator('img[alt="Latar Website"]')).not.toHaveAttribute('src', /^data:/);
  });
});