import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Sebutan Desa [Desa] tidak terbaca otomatis dari Pengaturan #10676', () => {
  test('fix: Sebutan Desa harus tampil sesuai pengaturan (tidak menampilkan "[Desa]")', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10676',
    },
  }, async ({ page }) => {
    // Buka halaman form identitas dan langsung ke tab profil
    await page.goto('identitas_desa/form#profil');

    const title = page.locator('h1');
    await expect(title).toBeVisible();
    // Pastikan teks tidak berisi literal [Desa]
    await expect(title).not.toContainText('[Desa]');

    // Periksa breadcrumb juga
    const breadcrumbLink = page.locator('.breadcrumb-item a').first();
    await expect(breadcrumbLink).toBeVisible();
    await expect(breadcrumbLink).not.toContainText('[Desa]');
  });
});
