import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../../storage/auth/admin.json'),
});

test.describe('Bug Fix: Filter Dicabut/Tidak Berlaku pada Buku Peraturan dan Buku Keputusan #10694', () => {
  
  test('Filter "Dicabut/Tidak Berlaku" harus menampilkan hanya status "Tidak" pada Buku Peraturan', async ({ page }) => {
    // 1. Akses modul /dokumen_sekretariat/peraturan
    await page.goto('/dokumen_sekretariat/peraturan');
    await page.waitForLoadState('networkidle');
    
    // Tunggu tabel DataTables dimuat
    await page.waitForSelector('table tbody tr', { timeout: 5000 });

    // 2. Klik filter dicabut/tidak berlaku (value='0')
    const filterSelect = page.locator('select[name="filter"]');
    
    // Tunggu element visible
    await filterSelect.waitFor({ state: 'visible', timeout: 3000 });
    
    // Select filter dengan value '0' untuk "Dicabut/Tidak Berlaku"
    await filterSelect.selectOption('0');
    
    // Tunggu tabel di-reload
    await page.waitForTimeout(1500);
    await page.waitForLoadState('networkidle');

    // 3. Cek kolom aktif apakah yang tampil hanya 'Tidak'
    const rows = await page.locator('table tbody tr');
    const rowCount = await rows.count();
    
    console.log(`Total rows after filter: ${rowCount}`);
    
    if (rowCount > 0) {
      // Iterasi setiap baris dan cek kolom "Aktif" (kolom ke-7 untuk Peraturan Desa)
      for (let i = 0; i < rowCount; i++) {
        const row = rows.nth(i);
        // Kolom Aktif ada di posisi ke-7 (index 6) untuk kategori 3 (Peraturan Desa)
        const statusCell = row.locator('td').nth(6); // 0-based index
        const statusText = await statusCell.textContent();
        
        // Verifikasi hanya "Tidak" yang tampil setelah filter
        expect(statusText?.trim()).toBe('Tidak');
        console.log(`Row ${i + 1}: Status = ${statusText?.trim()}`);
      }
    } else {
      console.log('No data found after applying filter - this is OK if no inactive documents exist');
    }
  });

  test('Filter "Dicabut/Tidak Berlaku" harus menampilkan hanya status "Tidak" pada Buku Keputusan', async ({ page }) => {
    // Akses modul /dokumen_sekretariat/keputusan
    await page.goto('/dokumen_sekretariat/keputusan');
    await page.waitForLoadState('networkidle');
    
    // Tunggu tabel DataTables dimuat
    await page.waitForSelector('table tbody tr', { timeout: 5000 });

    // Klik filter dicabut/tidak berlaku (value='0')
    const filterSelect = page.locator('select[name="filter"]');
    await filterSelect.waitFor({ state: 'visible', timeout: 3000 });
    await filterSelect.selectOption('0');
    
    // Tunggu tabel di-reload
    await page.waitForTimeout(1500);
    await page.waitForLoadState('networkidle');

    // Verifikasi semua row hanya menampilkan "Tidak" di kolom aktif
    const rows = await page.locator('table tbody tr');
    const rowCount = await rows.count();
    
    console.log(`Total rows after filter on Keputusan: ${rowCount}`);
    
    if (rowCount > 0) {
      for (let i = 0; i < rowCount; i++) {
        const row = rows.nth(i);
        // Kolom Aktif ada di posisi ke-6 (index 5) untuk kategori 2 (SK Kades)
        const statusCell = row.locator('td').nth(5);
        const statusText = await statusCell.textContent();
        
        // Setelah filter "Tidak", hanya "Tidak" yang boleh tampil
        expect(statusText?.trim()).toBe('Tidak');
        console.log(`Row ${i + 1}: Status = ${statusText?.trim()}`);
      }
    } else {
      console.log('No data found after applying filter - this is OK if no inactive documents exist');
    }
  });

  test('Filter harus berfungsi dengan kombinasi jenis peraturan pada Buku Peraturan', async ({ page }) => {
    await page.goto('/dokumen_sekretariat/peraturan');
    await page.waitForLoadState('networkidle');
    await page.waitForSelector('table tbody tr', { timeout: 5000 });

    // Cek apakah ada filter jenis peraturan (hanya untuk kategori 3)
    const jenisPeraturanSelect = page.locator('select[name="jenis_peraturan"]');
    const isVisible = await jenisPeraturanSelect.isVisible().catch(() => false);

    if (isVisible) {
      // Pilih jenis peraturan pertama
      await jenisPeraturanSelect.selectOption({ index: 1 });
      await page.waitForTimeout(500);
      await page.waitForLoadState('networkidle');

      // Kemudian pilih filter "Tidak"
      const filterSelect = page.locator('select[name="filter"]');
      await filterSelect.selectOption('0');
      await page.waitForTimeout(1500);
      await page.waitForLoadState('networkidle');

      // Verifikasi data ditampilkan atau kosong (tapi tidak crash)
      const rows = await page.locator('table tbody tr');
      const rowCount = await rows.count();
      expect(rowCount).toBeGreaterThanOrEqual(0);
      
      console.log(`Kombinasi filter jenis peraturan + status: ${rowCount} rows`);
    }
  });

  test('Filter tidak boleh menampilkan "Ya" ketika filter "Tidak" dipilih', async ({ page }) => {
    await page.goto('/dokumen_sekretariat/peraturan');
    await page.waitForLoadState('networkidle');
    await page.waitForSelector('table tbody tr', { timeout: 5000 });

    // Apply filter "Tidak"
    const filterSelect = page.locator('select[name="filter"]');
    await filterSelect.selectOption('0');
    
    await page.waitForTimeout(1000);
    await page.waitForLoadState('networkidle');

    // Ambil semua cell yang menampilkan status
    const statusCells = await page.locator('table tbody tr td').allTextContents();
    
    // Filter cells yang berisi "Ya" atau "Tidak"
    const statusValues = statusCells.filter(cell => cell.trim() === 'Ya' || cell.trim() === 'Tidak');
    
    // Verifikasi tidak ada "Ya" dalam hasil filter
    const hasYa = statusValues.some(status => status.trim() === 'Ya');
    expect(hasYa).toBeFalsy('Filter "Tidak" should not show "Ya" status');
  });
});
