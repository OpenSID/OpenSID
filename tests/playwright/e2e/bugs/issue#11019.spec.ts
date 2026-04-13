import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: banyaknya photo setiap produk di menu lapak tidak bisa di isi angka lebih dari 5 #11019', () => {
  test('fix: Banyak Foto Tiap Produk dapat diisi angka 20 dari menu pelapak settings modal', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11019',
    },
  }, async ({ page }) => {
    const TEST_VALUE = 20;
    const ERROR_MESSAGE = 'Please enter a value less than or equal to 5.';

    // 1. Navigate ke halaman /lapak_admin/pelapak
    await page.goto('/lapak_admin/pelapak');
    await page.waitForLoadState('networkidle');
    console.log('✅ Step 1: Navigasi ke /lapak_admin/pelapak');

    // 2. Cari dan klik tombol gear di pojok kanan atas untuk buka modal pengaturan
    // Tombol gear biasanya di box-header dengan icon fa-cog atau fa-gear
    let settingButton = page.locator('.box-header').first().locator('.fa-cog, .fa-gear').first();
    
    // Jika tidak ketemu, coba cari dengan selector alternatif
    if (!(await settingButton.isVisible())) {
      settingButton = page.locator('a[title*="Setting"], a[title*="setting"], button[title*="Setting"]').first();
    }
    
    await expect(settingButton).toBeVisible({ timeout: 5000 });
    await settingButton.click();
    console.log('✅ Step 2: Klik tombol gear settings di pojok kanan atas');

    // 3. Tunggu modal pengaturan muncul
    const modal = page.locator('[role="dialog"], .modal').first();
    await expect(modal).toBeVisible({ timeout: 5000 });
    console.log('✅ Step 3: Modal pengaturan tampil');

    // 4. Cari input field "Banyak Foto Tiap Produk" di dalam modal
    const photoCountInput = modal.locator('input[name="banyak_foto_tiap_produk"], #input_banyak_foto_tiap_produk');
    await expect(photoCountInput).toBeVisible();
    
    const currentValue = await photoCountInput.inputValue();
    console.log(`   Current value: ${currentValue}`);

    // 5. Clear dan input nilai 20
    await photoCountInput.clear();
    await photoCountInput.fill(TEST_VALUE.toString());
    await expect(photoCountInput).toHaveValue(TEST_VALUE.toString());
    console.log(`✅ Step 4: Input nilai ${TEST_VALUE} pada field Banyak Foto Tiap Produk`);

    // 6. Cari dan klik tombol simpan di dalam modal
    const saveButton = modal.locator('button:has-text("Simpan"), button:has-text("Save"), [type="submit"]').first();
    await expect(saveButton).toBeVisible();
    await saveButton.click();
    console.log('✅ Step 5: Klik tombol Simpan');

    // 7. Tunggu response dari server
    await page.waitForTimeout(1500);

    // 8. VERIFIKASI UTAMA: Tidak ada error message "Please enter a value less than or equal to 5."
    const errorMessageElement = page.locator(`text="${ERROR_MESSAGE}"`);
    const isErrorVisible = await errorMessageElement.isVisible({ timeout: 2000 }).catch(() => false);

    // 9. Check untuk validation errors atau alerts dengan pesan "less than or equal to"
    const allAlerts = page.locator('.alert, [role="alert"], .invalid-feedback, .form-error');
    let hasValidationError = false;

    const alertCount = await allAlerts.count();
    if (alertCount > 0) {
      for (let i = 0; i < alertCount; i++) {
        const alertText = await allAlerts.nth(i).textContent();
        if (alertText?.includes('less than or equal to') || alertText?.includes('5')) {
          hasValidationError = true;
          console.error(`❌ Found error alert: ${alertText}`);
          break;
        }
      }
    }

    // Test assertion - UTAMA: tidak ada error message validasi
    expect(!isErrorVisible && !hasValidationError).toBeTruthy();
    console.log('✅ Step 6 (VERIFIKASI UTAMA): Tidak ada error message "Please enter a value less than or equal to 5."');
    console.log('✅ TEST BERHASIL: Nilai 20 dapat disimpan tanpa error validasi!');
  });

  test('fix: Verifikasi max attribute adalah 20 (bukan 5)', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11019',
    },
  }, async ({ page }) => {
    const MAX_ALLOWED_VALUE = 20;

    // 1. Navigate ke halaman pengaturan global dengan kategori Lapak
    await page.goto('/pengaturan?kategori=Lapak');
    await page.waitForLoadState('networkidle');
    console.log('✅ Navigasi ke /pengaturan?kategori=Lapak');

    // 2. Cari input field "Banyak Foto Tiap Produk"
    const photoCountInput = page.locator('input#input_banyak_foto_tiap_produk');
    await expect(photoCountInput).toBeVisible();

    // 3. Verifikasi max attribute adalah 20 (bukan 5)
    const maxAttribute = await photoCountInput.getAttribute('max');
    const maxValue = parseInt(maxAttribute || '0');
    
    expect(maxValue).toBe(MAX_ALLOWED_VALUE);
    console.log(`✅ Max attribute: ${maxValue} (expected: ${MAX_ALLOWED_VALUE})`);

    // 4. Verifikasi min attribute adalah 1
    const minAttribute = await photoCountInput.getAttribute('min');
    const minValue = parseInt(minAttribute || '0');
    expect(minValue).toBe(1);
    console.log(`✅ Min attribute: ${minValue} (expected: 1)`);

    // 5. Verifikasi step adalah 1
    const stepAttribute = await photoCountInput.getAttribute('step');
    const stepValue = parseInt(stepAttribute || '0');
    expect(stepValue).toBe(1);
    console.log(`✅ Step attribute: ${stepValue} (expected: 1)`);

    console.log('✅ TEST BERHASIL: Semua attribute sudah diperbaiki dengan benar!');
  });
});
