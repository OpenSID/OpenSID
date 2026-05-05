import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: nomor register pada saat input data inventaris tidak konsisten #11125', () => {
  test('fix: nomor registrasi tetap konsisten saat mengubah jenis barang pada form inventaris tanah', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11125',
    },
  }, async ({ page }) => {
    // 1. Navigasi ke form inventaris tanah
    await page.goto('/inventaris_tanah/form');
    await page.waitForLoadState('networkidle');

    // 2. Tunggu sampai form loaded
    await expect(page.locator('#validasi')).toBeVisible();
    await page.waitForTimeout(500);

    // 3. Ambil dropdown nama barang
    const namaBarangSelect = page.locator('#nama_barang');
    await expect(namaBarangSelect).toBeVisible();

    // 4. Pilih opsi pertama dan catat nomor registrasi
    await namaBarangSelect.click();
    const options = await namaBarangSelect.locator('option').count();
    
    if (options > 1) {
      // Pilih opsi kedua
      await namaBarangSelect.selectOption({ index: 1 });
      await page.waitForTimeout(300);

      // 5. Ambil nilai nomor register setelah pilihan pertama
      const registerFieldFirstChoice = page.locator('#register');
      const registerValue1 = await registerFieldFirstChoice.inputValue();
      
      // 6. Ubah ke opsi ketiga (jika ada)
      if (options > 2) {
        await namaBarangSelect.selectOption({ index: 2 });
        await page.waitForTimeout(300);

        // 7. Ambil nilai nomor register setelah pilihan kedua
        const registerValue2 = await registerFieldFirstChoice.inputValue();

        // 8. Verifikasi bahwa nomor register TIDAK ditambahkan dengan nilai sebelumnya
        // (sebelum fix, registerValue2 akan memiliki registerValue1 di dalamnya)
        expect(registerValue2).not.toContain(registerValue1.slice(-6));
        expect(registerValue2.length).toBeLessThan(registerValue1.length + 7);

        // 9. Log untuk verifikasi manual
        console.log(`Nilai 1: ${registerValue1}, Nilai 2: ${registerValue2}`);
      }
    }
  });

  test('fix: nomor registrasi tetap konsisten saat mengubah jenis barang pada form inventaris peralatan', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11125',
    },
  }, async ({ page }) => {
    // 1. Navigasi ke form inventaris peralatan
    await page.goto('/inventaris_peralatan/form');
    await page.waitForLoadState('networkidle');

    // 2. Tunggu sampai form loaded
    await expect(page.locator('#validasi')).toBeVisible();
    await page.waitForTimeout(500);

    // 3. Ambil dropdown nama barang
    const namaBarangSelect = page.locator('#nama_barang');
    await expect(namaBarangSelect).toBeVisible();

    // 4. Pilih opsi dan catat nomor registrasi
    await namaBarangSelect.click();
    const options = await namaBarangSelect.locator('option').count();
    
    if (options > 1) {
      await namaBarangSelect.selectOption({ index: 1 });
      await page.waitForTimeout(300);

      const registerFieldFirstChoice = page.locator('#register');
      const registerValue1 = await registerFieldFirstChoice.inputValue();

      // 5. Ubah ke opsi lain
      if (options > 2) {
        await namaBarangSelect.selectOption({ index: 2 });
        await page.waitForTimeout(300);

        const registerValue2 = await registerFieldFirstChoice.inputValue();

        // 6. Verifikasi nomor register konsisten (tidak duplikasi dengan nilai sebelumnya)
        expect(registerValue2).not.toContain(registerValue1.slice(-6));
        expect(registerValue2.length).toBeLessThan(registerValue1.length + 7);
      }
    }
  });

  test('fix: nomor registrasi tetap konsisten saat mengubah jenis barang pada form inventaris gedung', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11125',
    },
  }, async ({ page }) => {
    // 1. Navigasi ke form inventaris gedung
    await page.goto('/inventaris_gedung/form');
    await page.waitForLoadState('networkidle');

    // 2. Tunggu sampai form loaded
    await expect(page.locator('#validasi')).toBeVisible();
    await page.waitForTimeout(500);

    // 3. Ambil dropdown nama barang
    const namaBarangSelect = page.locator('#nama_barang');
    await expect(namaBarangSelect).toBeVisible();

    // 4. Test perubahan opsi
    await namaBarangSelect.click();
    const options = await namaBarangSelect.locator('option').count();
    
    if (options > 1) {
      await namaBarangSelect.selectOption({ index: 1 });
      await page.waitForTimeout(300);

      const registerFieldFirstChoice = page.locator('#register');
      const registerValue1 = await registerFieldFirstChoice.inputValue();

      if (options > 2) {
        await namaBarangSelect.selectOption({ index: 2 });
        await page.waitForTimeout(300);

        const registerValue2 = await registerFieldFirstChoice.inputValue();

        // 5. Verifikasi tidak ada duplikasi nomor register
        expect(registerValue2).not.toContain(registerValue1.slice(-6));
        expect(registerValue2.length).toBeLessThan(registerValue1.length + 7);
      }
    }
  });

  test('fix: nomor registrasi tidak berubah saat mengubah kode barang/penggunaan barang', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11125',
    },
  }, async ({ page }) => {
    // 1. Navigasi ke form inventaris tanah
    await page.goto('/inventaris_tanah/form');
    await page.waitForLoadState('networkidle');

    // 2. Tunggu sampai form loaded
    await expect(page.locator('#validasi')).toBeVisible();
    await page.waitForTimeout(500);

    // 3. Ambil elemen yang diperlukan
    const namaBarangSelect = page.locator('#nama_barang');
    const penggunaanSelect = page.locator('#penggunaan_barang');
    const registerField = page.locator('#register');

    // 4. Pilih nama barang
    if (await namaBarangSelect.isVisible()) {
      await namaBarangSelect.click();
      const options = await namaBarangSelect.locator('option').count();
      
      if (options > 1) {
        await namaBarangSelect.selectOption({ index: 1 });
        await page.waitForTimeout(300);

        // 5. Catat nilai register
        const registerValue1 = await registerField.inputValue();

        // 6. Ubah penggunaan barang
        if (await penggunaanSelect.isVisible()) {
          await penggunaanSelect.click();
          const pengunaanOptions = await penggunaanSelect.locator('option').count();
          
          if (pengunaanOptions > 1) {
            await penggunaanSelect.selectOption({ index: 1 });
            await page.waitForTimeout(300);

            // 7. Verifikasi register tidak berubah (hanya kode barang yang berubah)
            const registerValue2 = await registerField.inputValue();
            expect(registerValue1).toBe(registerValue2);
          }
        }
      }
    }
  });
});
