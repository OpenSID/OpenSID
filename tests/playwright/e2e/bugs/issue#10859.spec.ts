import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug: Sebutan Dusun Tidak Tampil pada Pilihan Lokasi Pembangunan #10859', () => {
  test('fix: Dropdown Pilih Lokasi Pembangunan menampilkan sebutan dusun dengan prefix DUSUN', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10859',
    },
  }, async ({ page }) => {
    try {
      // Navigasi ke halaman form tambah pembangunan
      await page.goto('admin_pembangunan/form');

      // Tunggu dropdown lokasi pembangunan ter-load
      const lokasiDropdown = page.locator('#id_lokasi');
      await lokasiDropdown.waitFor({ state: 'visible' });

      // Klik dropdown untuk membuka pilihan
      await lokasiDropdown.click();

      // Tunggu opsi-opsi muncul
      await page.waitForTimeout(500);

      // Ambil semua opsi dropdown
      const options = await page.locator('#id_lokasi option').all();

      // Verifikasi bahwa setiap opsi (kecuali yang pertama) memiliki prefix "DUSUN"
      let hasValidDusunFormat = false;

      for (const option of options) {
        const optionText = await option.textContent();
        const optionValue = await option.getAttribute('value');

        // Skip opsi default (value kosong)
        if (!optionValue || optionValue === '') {
          continue;
        }

        // Verifikasi bahwa opsi memiliki prefix "DUSUN"
        if (optionText?.includes('DUSUN')) {
          hasValidDusunFormat = true;
        }
      }

      // Assert bahwa setidaknya ada satu opsi dengan format DUSUN
      expect(hasValidDusunFormat).toBe(true);

      // Verifikasi format lengkap: "DUSUN [NAMA] - RW [..] / RT [..]"
      const firstValidOption = await page.locator('#id_lokasi option[value!=""]').first();
      const firstOptionText = await firstValidOption.textContent();

      // Pastikan format dimulai dengan "DUSUN"
      expect(firstOptionText).toMatch(/^DUSUN\s/);
    } catch (error) {
      console.error('Test failed:', error);
      throw error;
    }
  });

  test('fix: Verifikasi format lengkap pilihan lokasi pembangunan', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10859',
    },
  }, async ({ page }) => {
    try {
      // Navigasi ke halaman form tambah pembangunan
      await page.goto('admin_pembangunan/form');

      // Tunggu dropdown lokasi pembangunan ter-load
      const lokasiDropdown = page.locator('#id_lokasi');
      await lokasiDropdown.waitFor({ state: 'visible' });

      // Ambil semua text dari opsi
      const optionsLocator = page.locator('#id_lokasi option');
      const optionsCount = await optionsLocator.count();

      // Verifikasi ada lebih dari 1 opsi (1 default + minimal 1 lokasi)
      expect(optionsCount).toBeGreaterThan(1);

      // Loop melalui setiap opsi dan verifikasi formatnya
      for (let i = 1; i < optionsCount; i++) {
        const optionText = await optionsLocator.nth(i).textContent();

        // Verifikasi format: dimulai dengan "DUSUN"
        expect(optionText).toBeDefined();
        expect(optionText?.trim()).toMatch(/^DUSUN\s/);

        // Verifikasi ada spasi setelah DUSUN (ada nama dusun)
        expect(optionText?.trim().length).toBeGreaterThan(6); // "DUSUN " + minimal ada 1 karakter nama
      }
    } catch (error) {
      console.error('Test failed:', error);
      throw error;
    }
  });

  test('fix: Dropdown dapat dipilih dengan prefix DUSUN', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10859',
    },
  }, async ({ page }) => {
    try {
      // Navigasi ke halaman form tambah pembangunan
      await page.goto('admin_pembangunan/form');

      // Tunggu dropdown lokasi pembangunan ter-load
      const lokasiDropdown = page.locator('#id_lokasi');
      await lokasiDropdown.waitFor({ state: 'visible' });

      // Cari opsi pertama yang valid (bukan default)
      const firstValidOption = page.locator('#id_lokasi option[value!=""]').first();
      const optionValue = await firstValidOption.getAttribute('value');
      const optionText = await firstValidOption.textContent();

      // Verifikasi format teks opsi
      expect(optionText).toMatch(/^DUSUN\s/);

      // Pilih opsi tersebut
      await lokasiDropdown.selectOption(optionValue || '');

      // Verifikasi opsi sudah terpilih
      const selectedValue = await lokasiDropdown.inputValue();
      expect(selectedValue).toBe(optionValue);
    } catch (error) {
      console.error('Test failed:', error);
      throw error;
    }
  });
});
