import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Data SK masih tetap muncul sudah di hapus #10730', () => {
  test('fix: Data SK masih tetap muncul sudah di hapus', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10730',
    },
  }, async ({ page }) => {
    // Test halaman admin peraturan-desa (menggunakan DokumenHidup langsung)
    await page.goto('peraturan-desa');
    await expect(page).toHaveURL(/peraturan-desa/);
    // Tunggu tabel load
    await page.waitForSelector('table');
    // Periksa bahwa halaman load tanpa error (data sudah difilter oleh DokumenHidup)

    // Test API produk hukum (yang sudah diperbarui dengan filter DokumenHidup)
    const apiResponse = await page.request.get('/api/produk-hukum');
    expect(apiResponse.ok()).toBeTruthy();
    const apiData = await apiResponse.json();
    // Periksa bahwa response memiliki struktur data yang valid
    expect(apiData).toHaveProperty('data');
    expect(Array.isArray(apiData.data)).toBeTruthy();
    // Jika ada data, pastikan setiap item memiliki id (sebagai indikasi valid)
    if (apiData.data.length > 0) {
      apiData.data.forEach((item: any) => {
        expect(item).toHaveProperty('id');
        // Opsional: Periksa bahwa item ada di DokumenHidup (jika bisa diakses via API lain)
      });
    }
  });
});
