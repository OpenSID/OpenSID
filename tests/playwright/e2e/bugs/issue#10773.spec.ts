import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Pilihan pada pendaftaran anjungan belum optimal di tampilan Mobile #10773', () => {
  test('fix: perbaikan Pilihan pada pendaftaran anjungan belum optimal di tampilan Mobile', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10773',
    },
  }, async ({ page }) => {
    // Set viewport ke ukuran mobile untuk menguji responsivitas
    await page.setViewportSize({ width: 375, height: 667 });

    await page.goto('/anjungan/form');

    // 1. Verifikasi grup "Orientasi Layar"
    const orientasiGroup = page.locator('.form-group', { hasText: 'Orientasi Layar' });
    const lanskapButton = orientasiGroup.locator('label', { hasText: 'Lanskap' });
    const potretButton = orientasiGroup.locator('label', { hasText: 'Potret' });

    // Pastikan kedua tombol terlihat
    await expect(lanskapButton).toBeVisible();
    await expect(potretButton).toBeVisible();

    // Ambil posisi dan ukuran tombol
    const lanskapBox = await lanskapButton.boundingBox();
    const potretBox = await potretButton.boundingBox();

    // Pastikan bounding box tidak null
    expect(lanskapBox).not.toBeNull();
    expect(potretBox).not.toBeNull();

    // Di tampilan mobile (col-xs-6), tombol harus berdampingan di baris yang sama
    if (lanskapBox && potretBox) {
      // Pastikan posisi Y (vertikal) hampir sama (berada di satu baris)
      expect(Math.abs(lanskapBox.y - potretBox.y)).toBeLessThan(5);

      // Pastikan tombol "Potret" berada di sebelah kanan tombol "Lanskap"
      expect(potretBox.x).toBeGreaterThan(lanskapBox.x);

      // Pastikan tidak ada tumpang tindih (overlap)
      expect(lanskapBox.x + lanskapBox.width).toBeLessThanOrEqual(potretBox.x + 1);
    }
  });
});
