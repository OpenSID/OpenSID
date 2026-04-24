import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Pemanggilan foto default jika area tidak punya foto #11080', () => {
  test('fix: tampilkan foto fallback pada form area saat foto kosong', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11080',
    },
  }, async ({ page }) => {
    // Data bawaan Playwright: area id=1 (ref_polygon=3, parent=1) memiliki kolom foto kosong.
    await page.goto('/area/form/1/1');

    const fotoPreview = page.locator('img.attachment-img');

    // Sebelum perbaikan, src bisa mengarah ke folder area (broken image).
    // Sesudah perbaikan, src wajib fallback ke 404-image-not-found.jpg.
    await expect(fotoPreview).toBeVisible();
    await expect(fotoPreview).toHaveAttribute('src', /404-image-not-found\.jpg$/);

    const naturalWidth = await fotoPreview.evaluate((img: HTMLImageElement) => img.naturalWidth);
    expect(naturalWidth).toBeGreaterThan(0);
  });
});
