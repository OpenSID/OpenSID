import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: data ayah tidak bisa diinput manual #10839', () => {
  test('fix: Nama ayah dapat diinput manual saat data ayah tidak ada dalam KK', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10839',
    },
  }, async ({ page }) => {
    // Buka form bayi baru lahir (peristiwa = 1)
    await page.goto('keluarga/form_peristiwa/1/1');

    // Tunggu page selesai loading
    await page.waitForLoadState('networkidle');

    // Step 1: Pilih Hubungan Dalam Keluarga = "Anak"
    const kk_levelSelect = page.locator('#kk_level');
    await kk_levelSelect.selectOption('4'); // 4 = Anak (SHDKEnum::ANAK)
    
    // Tunggu script orang_tua() dieksekusi
    await page.waitForTimeout(500);

    // Step 2: Verifikasi dan test input field NIK Ayah
    const ayahNikField = page.locator('#ayah_nik');
    
    // Cek apakah field editable (tidak readonly)
    const isAyahNikReadonly = await ayahNikField.evaluate((el: HTMLInputElement) => el.readOnly);
    expect(isAyahNikReadonly).toBe(false);
    
    // Input data NIK Ayah
    await ayahNikField.fill('1234567890123456');
    
    // Verifikasi nilai tersimpan
    const nikValue = await ayahNikField.inputValue();
    expect(nikValue).toBe('1234567890123456');

    // Step 3: Verifikasi dan test input field Nama Ayah
    const namaAyahField = page.locator('#nama_ayah');
    
    // Cek apakah field editable (tidak readonly)
    const isNamaAyahReadonly = await namaAyahField.evaluate((el: HTMLInputElement) => el.readOnly);
    expect(isNamaAyahReadonly).toBe(false);
    
    // Input data Nama Ayah
    await namaAyahField.fill('Ayah Dari Luar KK');
    
    // Verifikasi nilai tersimpan (dikonversi ke uppercase)
    const namaValue = await namaAyahField.inputValue();
    expect(namaValue).toBe('AYAH DARI LUAR KK');

    // Step 4: Verifikasi field Ibu juga dapat diinput
    const ibuNikField = page.locator('#ibu_nik');
    
    // Cek apakah field editable (tidak readonly)
    const isIbuNikReadonly = await ibuNikField.evaluate((el: HTMLInputElement) => el.readOnly);
    expect(isIbuNikReadonly).toBe(false);
    
    // Input data NIK Ibu
    await ibuNikField.fill('9876543210987654');
    
    // Verifikasi nilai tersimpan
    const ibuNikValue = await ibuNikField.inputValue();
    expect(ibuNikValue).toBe('9876543210987654');

    // Field Nama Ibu juga dapat diinput
    const namaIbuField = page.locator('#nama_ibu');
    
    // Cek apakah field editable (tidak readonly)
    const isNamaIbuReadonly = await namaIbuField.evaluate((el: HTMLInputElement) => el.readOnly);
    expect(isNamaIbuReadonly).toBe(false);
    
    // Input data Nama Ibu
    await namaIbuField.fill('Ibu Dari Luar KK');
    
    // Verifikasi nilai tersimpan (dikonversi ke uppercase)
    const namaIbuValue = await namaIbuField.inputValue();
    expect(namaIbuValue).toBe('IBU DARI LUAR KK');
  });
});
