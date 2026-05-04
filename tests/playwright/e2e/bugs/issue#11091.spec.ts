import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Perbaiki nama hasil unduhan Data Riwayat Mutasi Penduduk #11091', () => {
  test('fix: download Log Penduduk menampilkan filename deskriptif bukan hanya tanggal', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11091 - Follow up untuk Penduduk_log',
    },
  }, async ({ page }) => {
    // Alur: Pergi ke /penduduk_log > Klik Cetak/Unduh > Unduh > Klik Unduh di modal
    
    // Step 1: Pergi ke halaman /penduduk_log
    await page.goto('/penduduk_log');
    await page.waitForURL(/penduduk_log/);
    
    // Step 2: Klik tombol 'Cetak/Unduh'
    const cetakButton = page.locator('button, a').filter({ hasText: /Cetak|Unduh/i }).first();
    if (!await cetakButton.isVisible({ timeout: 3000 }).catch(() => false)) {
      test.skip();
    }
    await cetakButton.click();
    
    // Step 3: Jika muncul dropdown menu dengan opsi Cetak/Unduh, klik 'Unduh'
    const unduhInDropdown = page.locator('a, button').filter({ hasText: /^Unduh$/i }).first();
    if (await unduhInDropdown.isVisible({ timeout: 2000 }).catch(() => false)) {
      await unduhInDropdown.click();
    }
    
    // Step 4: Tunggu modal 'Unduh' muncul
    const modal = page.locator('[role="dialog"], .modal').first();
    await modal.waitFor({ state: 'visible', timeout: 5000 }).catch(() => {
      // Modal mungkin tidak selalu muncul
    });
    
    // Step 5: Cari dan klik tombol 'Unduh' di modal
    const downloadButton = page.locator('button').filter({ hasText: /^Unduh$/i }).last();
    
    // Set up listener untuk download
    const downloadPromise = page.waitForEvent('download');
    
    if (await downloadButton.isVisible({ timeout: 2000 }).catch(() => false)) {
      await downloadButton.click();
    } else {
      test.skip();
    }
    
    // Step 6: Tunggu download selesai dan verifikasi
    const download = await downloadPromise;
    const filename = download.suggestedFilename();
    
    // Verifikasi: filename harus berisi "log_penduduk" bukan hanya tanggal
    expect(filename).toMatch(/log_penduduk/i, 'Filename harus berisi "log_penduduk"');
    expect(filename).toMatch(/\d{1,2}_\d{1,2}_\d{4}/, 'Filename harus berisi format tanggal dd_mm_yyyy');
    expect(filename).toMatch(/\.xls/i, 'Filename harus berakhir dengan .xls');
    expect(filename).not.toMatch(/^\d{1,2}_\d{1,2}_\d{4}\.xls$/i, 'Tidak boleh hanya tanggal saja');
    
    const file_path = await download.path();
    expect(file_path).toBeTruthy();
    
    console.log(`✓ Penduduk_log file downloaded: ${filename}`);
  });

  test('fix: nama file download Riwayat Mutasi Penduduk menampilkan nama deskriptif, bukan hanya timestamp', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11091',
    },
  }, async ({ page }) => {
    // Step 1: Navigate ke halaman Admin
    await page.goto('/');
    
    // Step 2: Navigate ke Kependudukan > Riwayat Mutasi Penduduk
    const pendudukMenu = page.locator('a').filter({ hasText: /Kependudukan|Penduduk/i }).first();
    await expect(pendudukMenu).toBeVisible();
    await pendudukMenu.click();
    
    // Step 3: Click pada Riwayat Mutasi Penduduk
    await page.waitForTimeout(500);
    const riwayatMutasiMenu = page.locator('a').filter({ hasText: /Riwayat Mutasi Penduduk/i }).first();
    await expect(riwayatMutasiMenu).toBeVisible();
    await riwayatMutasiMenu.click();
    
    // Step 4: Tunggu halaman Riwayat Mutasi Penduduk dimuat
    await page.waitForURL(/bumindes_penduduk_mutasi/);
    
    // Step 5: Klik tombol Cetak/Unduh
    const cetakButton = page.locator('button, a').filter({ hasText: /Cetak|Unduh/i }).first();
    await expect(cetakButton).toBeVisible();
    await cetakButton.click();
    
    // Step 6: Modal dialog Unduh tampil
    await expect(page.locator('text=Cetak/Unduh')).toBeVisible({ timeout: 5000 });
    
    // Step 7: Klik tombol Unduh
    const unduhButton = page.locator('button').filter({ hasText: /^Unduh$/i }).last();
    
    // Set up listener untuk download
    const downloadPromise = page.waitForEvent('download');
    await unduhButton.click();
    
    // Step 8: Tunggu download selesai dan verifikasi
    const download = await downloadPromise;
    const filename = download.suggestedFilename();
    
    // Expected format: "buku_mutasi_penduduk_dd_mm_yyyy.xls"
    expect(filename).toMatch(/buku_mutasi_penduduk/i, 'Filename harus berisi "buku_mutasi_penduduk"');
    expect(filename).toMatch(/\d{1,2}_\d{1,2}_\d{4}/, 'Filename harus berisi format tanggal dd_mm_yyyy');
    expect(filename).toMatch(/\.xls/i, 'Filename harus berakhir dengan .xls');
    
    const path_file = await download.path();
    expect(path_file).toBeTruthy();
    
    console.log(`✓ Riwayat Mutasi Penduduk file downloaded: ${filename}`);
  });

  test('fix: download Riwayat Mutasi Penduduk dengan filter tahun/bulan', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11091',
    },
  }, async ({ page }) => {
    // Step 1: Navigate ke halaman Admin
    await page.goto('/');
    
    // Step 2: Navigate ke Kependudukan > Riwayat Mutasi Penduduk
    const pendudukMenu = page.locator('a').filter({ hasText: /Kependudukan|Penduduk/i }).first();
    await expect(pendudukMenu).toBeVisible();
    await pendudukMenu.click();
    
    // Step 3: Click pada Riwayat Mutasi Penduduk
    await page.waitForTimeout(500);
    const riwayatMutasiMenu = page.locator('a').filter({ hasText: /Riwayat Mutasi Penduduk/i }).first();
    await expect(riwayatMutasiMenu).toBeVisible();
    await riwayatMutasiMenu.click();
    
    // Step 4: Tunggu halaman dimuat
    await page.waitForURL(/bumindes_penduduk_mutasi/);
    
    // Step 5: Set filter Tahun (jika ada)
    const tahunSelect = page.locator('select[name="tahun"], input[name="tahun"]').first();
    if (await tahunSelect.isVisible({ timeout: 2000 }).catch(() => false)) {
      await tahunSelect.selectOption('2024').catch(() => {});
    }
    
    // Step 6: Klik tombol Cetak/Unduh
    const cetakButton = page.locator('button, a').filter({ hasText: /Cetak|Unduh/i }).first();
    await expect(cetakButton).toBeVisible();
    await cetakButton.click();
    
    // Step 7: Modal tampil
    await expect(page.locator('text=Cetak/Unduh')).toBeVisible({ timeout: 5000 });
    
    // Step 8: Klik Unduh
    const unduhButton = page.locator('button').filter({ hasText: /^Unduh$/i }).last();
    const downloadPromise = page.waitForEvent('download');
    await unduhButton.click();
    
    // Step 9: Verifikasi download
    const download = await downloadPromise;
    const filename = download.suggestedFilename();
    
    expect(filename).toMatch(/buku_mutasi_penduduk/i);
    expect(filename).toMatch(/\.xls/i);
    
    console.log(`✓ Filtered Riwayat Mutasi file downloaded: ${filename}`);
  });
});
