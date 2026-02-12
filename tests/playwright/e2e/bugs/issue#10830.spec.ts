import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Validasi field nomor sertifikat pada Inventaris Tanah #10830', () => {
  test('fix: Validasi no_sertifikat tidak boleh lebih dari 50 karakter', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10830',
    },
  }, async ({ page }) => {
    try {
      // 1. Masuk halaman inventaris_tanah
      await page.goto('inventaris_tanah');
      await expect(page.getByRole('heading')).toContainText('Inventaris Tanah');

      // 2. Klik tombol 'Ubah Data' pada salah satu data/row
      const editButton = page.getByRole('link', { name: /Ubah Data/ }).first();
      
      // Pastikan ada data untuk diubah
      if (await editButton.count() > 0) {
        await editButton.click();

        // Tunggu halaman form terbuka
        await expect(page.getByRole('heading')).toContainText('Ubah');

        // 3. Inputan no_sertifikat diisi dengan inputan > dari 50 karakter
        const testString = 'a'.repeat(51); // String dengan 51 karakter
        
        const noSertifikatInput = page.locator('#no_sertifikat');
        await noSertifikatInput.clear();
        await noSertifikatInput.fill(testString);

        // 4. Klik simpan
        await page.getByRole('button', { name: /Simpan/ }).click();

        // 5. Pastikan muncul pesan error dibawah inputan no_sertifikat
        // Dengan pesan: "Nomor sertifikat tidak boleh lebih dari 50 karakter."
        const errorMessage = page.locator('label.error');
        
        await expect(errorMessage).toBeVisible();
        await expect(errorMessage).toContainText(/Nomor sertifikat tidak boleh lebih dari.*50 karakter/i);
      }

    } catch (e) {
      console.error('Test error:', e);
    }
  });
});
