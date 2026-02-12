import { test, expect } from '@playwright/test';
import path from 'path';
import { Laravel } from '@test/utils/laravel';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('TypeError pada PermohonanSuratSubmitted dengan PendudukMandiri #10833', () => {
  test.beforeEach(async () => {
    // Siapkan format surat yang bisa diakses layanan mandiri
    await Laravel.query(`
      UPDATE formatsurat SET mandiri = 1 WHERE id = 1 LIMIT 1;
    `);
  });

  test.afterEach(async () => {
    // Hapus data permohonan surat yang dibuat saat test
    await Laravel.query(`
      DELETE FROM permohonan_surat WHERE keterangan = 'Test permohonan surat - Issue #10833';
    `);
  });

  test('fix: PermohonanSuratSubmitted harus menerima baik Penduduk maupun PendudukMandiri', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10833'
    }
  }, async ({ page }) => {

    // 1. Masuk ke layanan mandiri
    await page.goto('layanan-mandiri/masuk');

    try {
      await page.getByText('Terima semua cookie', { exact: true }).click();
    } catch { } // abaikan kalau tombol cookie nggak ada

    try {
      // 2. Login dengan NIK dan PIN pengguna mandiri
      await page.getByRole('textbox', { name: 'NIK' }).fill('1505022111940001');
      await page.getByRole('textbox', { name: 'PIN' }).fill('123456');
      await page.getByRole('button', { name: 'MASUK', exact: true }).click();
      
      // Tunggu redirect setelah login berhasil
      await page.waitForNavigation({ waitUntil: 'networkidle' });

      // 3. Navigasi ke halaman buat surat
      await page.goto('layanan-mandiri/permohonan-surat');

      // 4. Klik tombol Buat Surat
      await page.getByRole('link', { name: /Buat Surat/i }).click();
      await page.waitForLoadState('networkidle');

      // 5. Isi form permohonan surat
      // Pilih jenis surat dari dropdown
      const suratDropdown = page.locator('select[name*="surat"], select[name*="url_surat"], [role="combobox"]:first-of-type');
      await suratDropdown.first().click();
      
      // Pilih option pertama yang tersedia
      const options = page.locator('option, [role="option"]');
      const optionCount = await options.count();
      
      if (optionCount > 1) {
        await options.nth(1).click();
      }

      // 6. Isi keterangan tambahan
      await page.getByPlaceholder(/keterangan|Keterangan/i).fill('Test permohonan surat - Issue #10833');

      // 7. Isi nomor HP aktif
      await page.getByPlaceholder(/No\.\s*HP|HP aktif|nomor hp/i).fill('081234567890');

      // 8. Klik tombol "Isi Form"
      await page.getByRole('button', { name: /Isi Form|Lanjutkan/i }).click();
      await page.waitForLoadState('networkidle');

      // 9. Isi form isian dari surat (sesuaikan dengan field yang ada)
      // Cari semua input field dan isi dengan data test
      const inputs = page.locator('input[type="text"]:not([readonly]), input[type="email"], input[type="tel"], input[type="number"]');
      const inputCount = await inputs.count();
      
      for (let i = 0; i < inputCount; i++) {
        try {
          const placeholder = await inputs.nth(i).getAttribute('placeholder');
          const type = await inputs.nth(i).getAttribute('type');
          
          // Jangan isi field yang readonly atau sudah memiliki value
          const currentValue = await inputs.nth(i).inputValue();
          if (!currentValue) {
            if (type === 'email') {
              await inputs.nth(i).fill('test@desa.id');
            } else if (type === 'tel') {
              await inputs.nth(i).fill('081234567890');
            } else if (type === 'number') {
              await inputs.nth(i).fill('0');
            } else {
              await inputs.nth(i).fill('Test Data');
            }
          }
        } catch { }
      }

      // 9. Klik tombol "Kirim"
      await page.getByRole('button', { name: /Kirim|Submit/i }).click();
      
      // Tunggu redirect
      await page.waitForNavigation({ waitUntil: 'networkidle' });

      // 10. Verifikasi berhasil redirect ke halaman permohonan-surat
      expect(page.url()).toContain('layanan-mandiri/permohonan-surat');

      // 11. Verifikasi tidak ada error TypeError
      const errorMessages = page.locator('text=/TypeError|error/i');
      await expect(errorMessages).not.toBeVisible();

    } catch (error) {
      // Jika ada error, log untuk debug
      console.error('Test failed:', error);
      throw error;
    }
  });
});
