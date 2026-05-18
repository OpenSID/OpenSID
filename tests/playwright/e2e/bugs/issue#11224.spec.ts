import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Perbaiki nama hasil unduhan Data Wilayah Administratif #11224', () => {
  test('fix: download Data Wilayah Administratif menampilkan filename deskriptif bukan hanya tanggal', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11224',
    },
  }, async ({ page }) => {
    // Alur:
    // 1. Pergi ke: wilayah
    // 2. Klik Cetak/Unduh
    // 3. Klik Unduh
    // 4. Tunggu modal 'Unduh Data' muncul
    // 5. Klik tombol Unduh
    // 6. Verifikasi: ada prefix sebelum tanggal, bukan hanya timestamp

    // Step 1: Pergi ke halaman /wilayah (Info Desa > Wilayah Administratif)
    await page.goto('/wilayah');
    await page.waitForURL(/wilayah/, { timeout: 10000 });
    
    // Tunggu halaman sepenuhnya dimuat
    await page.waitForLoadState('networkidle');

    // Step 2: Cari tombol 'Cetak/Unduh' dan klik
    const cetakUnduhButton = page.locator('a, button').filter({ hasText: /Cetak\/Unduh|Cetak/i }).first();
    
    if (!await cetakUnduhButton.isVisible({ timeout: 5000 }).catch(() => false)) {
      console.log('Tombol Cetak/Unduh tidak ditemukan, cek dropdown');
      const dropdown = page.locator('button, .btn-group-vertical').first();
      await dropdown.click();
    } else {
      await cetakUnduhButton.click();
    }

    // Step 3: Klik menu 'Unduh' dari dropdown
    const unduhMenu = page.locator('a, button').filter({ hasText: /^Unduh$/i }).first();
    
    if (await unduhMenu.isVisible({ timeout: 3000 }).catch(() => false)) {
      // Set up listener untuk download
      const downloadPromise = page.waitForEvent('download');
      
      await unduhMenu.click();
      
      // Step 4-5: Tunggu modal dan klik tombol Unduh
      // Modal mungkin muncul atau langsung download dimulai
      const modalUnduh = page.locator('[role="dialog"], .modal').locator('button').filter({ hasText: /^Unduh$/i });
      
      if (await modalUnduh.isVisible({ timeout: 3000 }).catch(() => false)) {
        // Modal muncul, klik tombol Unduh di dalamnya
        await modalUnduh.click();
      }
      
      // Step 6: Tunggu download selesai dan verifikasi
      const download = await downloadPromise;
      const filename = download.suggestedFilename();
      
      console.log(`📥 Downloaded filename: ${filename}`);
      
      // Verifikasi: filename harus berisi prefix deskriptif sebelum tanggal
      // Format yang diharapkan: data_wilayah_administratif_DD_MM_YYYY.xls
      
      // ❌ Salah: _18_05_2026.xls (hanya tanggal)
      // ✅ Benar: data_wilayah_administratif_18_05_2026.xls (ada prefix)
      
      expect(filename).not.toMatch(/^_\d{1,2}_\d{1,2}_\d{4}\.xls$/i, 
        'Tidak boleh hanya tanggal saja (salah: _18_05_2026.xls)');
      
      expect(filename).toMatch(/data_wilayah_administratif/i,
        'Filename harus berisi prefix "data_wilayah_administratif"');
      
      expect(filename).toMatch(/\d{1,2}_\d{1,2}_\d{4}/,
        'Filename harus berisi format tanggal dd_mm_yyyy');
      
      expect(filename).toMatch(/\.xls$/i,
        'Filename harus berakhir dengan .xls');
      
      // Verifikasi bahwa file benar-benar ada
      const file_path = await download.path();
      expect(file_path).toBeTruthy();
      
      console.log(`✅ PASS: File unduhan memiliki prefix deskriptif: ${filename}`);
    } else {
      console.log('Tombol Unduh tidak ditemukan di menu');
      test.skip();
    }
  });

  test('fix: download Data Wilayah RW menampilkan filename dengan konteks RW dan dusun', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11224 - RW level download',
    },
  }, async ({ page }) => {
    // Test untuk RW level unduh
    
    // Step 1: Pergi ke halaman /wilayah (dusun level)
    await page.goto('/wilayah');
    await page.waitForURL(/wilayah/, { timeout: 10000 });
    await page.waitForLoadState('networkidle');

    // Step 2: Klik salah satu dusun untuk masuk ke level RW
    const firstDusun = page.locator('table tbody tr').first().locator('a, button').first();
    
    if (await firstDusun.isVisible({ timeout: 3000 }).catch(() => false)) {
      await firstDusun.click();
      
      // Tunggu halaman RW dimuat
      await page.waitForLoadState('networkidle');
      
      // Step 3: Cari tombol Cetak/Unduh di level RW
      const cetakUnduhButton = page.locator('a, button').filter({ hasText: /Cetak\/Unduh|Cetak/i }).first();
      
      if (await cetakUnduhButton.isVisible({ timeout: 3000 }).catch(() => false)) {
        await cetakUnduhButton.click();
        
        // Step 4: Klik menu 'Unduh' dari dropdown
        const unduhMenu = page.locator('a, button').filter({ hasText: /^Unduh$/i }).first();
        
        if (await unduhMenu.isVisible({ timeout: 3000 }).catch(() => false)) {
          const downloadPromise = page.waitForEvent('download');
          await unduhMenu.click();
          
          const download = await downloadPromise;
          const filename = download.suggestedFilename();
          
          console.log(`📥 Downloaded RW filename: ${filename}`);
          
          // Verifikasi RW filename
          expect(filename).toMatch(/data_wilayah_administratif_rw/i,
            'Filename RW harus berisi "data_wilayah_administratif_rw"');
          
          expect(filename).not.toMatch(/^_\d{1,2}_\d{1,2}_\d{4}\.xls$/i,
            'Tidak boleh hanya tanggal saja');
          
          expect(filename).toMatch(/\d{1,2}_\d{1,2}_\d{4}/,
            'Filename harus berisi format tanggal dd_mm_yyyy');
          
          console.log(`✅ PASS: File RW memiliki prefix deskriptif: ${filename}`);
        }
      }
    } else {
      console.log('Tidak ada data dusun untuk test RW level');
      test.skip();
    }
  });

  test('fix: download Data Wilayah RT menampilkan filename dengan konteks RT, RW dan dusun', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11224 - RT level download',
    },
  }, async ({ page }) => {
    // Test untuk RT level unduh
    
    // Step 1: Pergi ke halaman /wilayah (dusun level)
    await page.goto('/wilayah');
    await page.waitForURL(/wilayah/, { timeout: 10000 });
    await page.waitForLoadState('networkidle');

    // Step 2: Klik salah satu dusun untuk masuk ke level RW
    const firstDusun = page.locator('table tbody tr').first().locator('a, button').first();
    
    if (await firstDusun.isVisible({ timeout: 3000 }).catch(() => false)) {
      await firstDusun.click();
      await page.waitForLoadState('networkidle');
      
      // Step 3: Klik salah satu RW untuk masuk ke level RT
      const firstRW = page.locator('table tbody tr').first().locator('a, button').first();
      
      if (await firstRW.isVisible({ timeout: 3000 }).catch(() => false)) {
        await firstRW.click();
        await page.waitForLoadState('networkidle');
        
        // Step 4: Cari tombol Cetak/Unduh di level RT
        const cetakUnduhButton = page.locator('a, button').filter({ hasText: /Cetak\/Unduh|Cetak/i }).first();
        
        if (await cetakUnduhButton.isVisible({ timeout: 3000 }).catch(() => false)) {
          await cetakUnduhButton.click();
          
          // Step 5: Klik menu 'Unduh' dari dropdown
          const unduhMenu = page.locator('a, button').filter({ hasText: /^Unduh$/i }).first();
          
          if (await unduhMenu.isVisible({ timeout: 3000 }).catch(() => false)) {
            const downloadPromise = page.waitForEvent('download');
            await unduhMenu.click();
            
            const download = await downloadPromise;
            const filename = download.suggestedFilename();
            
            console.log(`📥 Downloaded RT filename: ${filename}`);
            
            // Verifikasi RT filename
            expect(filename).toMatch(/data_wilayah_administratif_rt/i,
              'Filename RT harus berisi "data_wilayah_administratif_rt"');
            
            expect(filename).not.toMatch(/^_\d{1,2}_\d{1,2}_\d{4}\.xls$/i,
              'Tidak boleh hanya tanggal saja');
            
            expect(filename).toMatch(/\d{1,2}_\d{1,2}_\d{4}/,
              'Filename harus berisi format tanggal dd_mm_yyyy');
            
            console.log(`✅ PASS: File RT memiliki prefix deskriptif: ${filename}`);
          }
        }
      }
    } else {
      console.log('Tidak ada data dusun untuk test RT level');
      test.skip();
    }
  });
});
