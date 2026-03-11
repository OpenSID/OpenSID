import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Malformasi Daftar Aset pada Modul Pemetaan (GIS) #10914', () => {
  test('fix: tombol Salin tidak menyalin file non-gambar (index.html) ke daftar simbol', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10914',
    },
  }, async ({ page }) => {
    // 1. Buka halaman Simbol Lokasi
    await page.goto('simbol');
    await page.waitForLoadState('networkidle');

    // 2. Klik tombol 'Salin' untuk menyalin simbol default
    await page.getByRole('link', { name: /salin/i }).click();
    await page.waitForLoadState('networkidle');

    // 3. Kumpulkan semua label simbol yang tampil di halaman
    const simbolLabels = await page.locator('ul#icons .glyphicon-class').allTextContents();

    // Pastikan ada simbol yang berhasil disalin
    expect(simbolLabels.length).toBeGreaterThan(0);

    // Pastikan 'index.html' TIDAK ada dalam daftar simbol
    const hasIndexHtml = simbolLabels.some(label => label.trim().toLowerCase() === 'index.html');
    expect(hasIndexHtml).toBe(false);

    // Pastikan semua simbol yang tampil memiliki ekstensi gambar yang valid
    const imageExtensions = ['png', 'jpg', 'jpeg', 'gif', 'webp'];
    for (const label of simbolLabels) {
      const ext = label.trim().split('.').pop()?.toLowerCase() ?? '';
      expect(imageExtensions).toContain(ext);
    }
  });
});
