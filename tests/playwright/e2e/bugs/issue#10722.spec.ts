import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Error 500 saat cetak buku administrasi penduduk dengan filter tahun/bulan kosong #10722', () => {
  test('fix: Cetak buku induk penduduk dengan filter tahun kosong tidak error 500', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10722',
    },
  }, async ({ page }) => {
    // 1. Masuk ke modul bumindes_penduduk_induk
    await page.goto('bumindes_penduduk_induk');
    await expect(page.getByRole('heading', { name: 'Buku Induk Penduduk' })).toBeVisible();

    // 2. Pastikan select tahun ada dan set ke "Pilih Tahun" (value kosong)
    const selectTahun = page.locator('#tahun');
    await expect(selectTahun).toBeVisible();
    await selectTahun.selectOption('');

    // Pastikan select bulan tersembunyi saat tahun kosong
    const selectBulan = page.locator('#bulan');
    const isHidden = await selectBulan.evaluate(el => {
      const parent = el.parentElement;
      return parent ? window.getComputedStyle(parent).display === 'none' : false;
    });
    expect(isHidden).toBeTruthy();

    // 3. Klik tombol Cetak/Unduh
    const cetakUnduhButton = page.getByRole('button', { name: /Cetak\/Unduh/i });
    await expect(cetakUnduhButton).toBeVisible();
    await cetakUnduhButton.click();

    // 4. Pilih menu Cetak dari dropdown
    const cetakLink = page.locator('a[href*="bumindes_penduduk_induk/dialog/cetak"]').first();
    await expect(cetakLink).toBeVisible();
    
    // Listen untuk modal dialog
    const modalPromise = page.waitForSelector('#modalBox .modal-dialog', { timeout: 5000 });
    await cetakLink.click();
    await modalPromise;

    // Tunggu modal dialog cetak muncul
    await expect(page.locator('#modalBox')).toBeVisible();
    
    // Klik tombol cetak di modal
    const submitButton = page.locator('#modalBox button[type="submit"]').first();
    await expect(submitButton).toBeVisible();
    
    // Listen untuk popup window baru
    const [newPage] = await Promise.all([
      page.waitForEvent('popup'),
      submitButton.click()
    ]);

    // 5. Tunggu halaman cetak terbuka dan verifikasi tidak ada error 500
    await newPage.waitForLoadState('networkidle');
    
    // Cek tidak ada error 500
    const pageContent = await newPage.content();
    expect(pageContent).not.toContain('HTTP ERROR 500');
    expect(pageContent).not.toContain('500 Internal Server Error');
    
    // Verifikasi halaman cetak berhasil dimuat
    await expect(newPage.locator('body')).toBeVisible();
    
    // Verifikasi ada konten yang expected di halaman cetak
    const hasPrintContent = await newPage.locator('.judul, table').count() > 0;
    expect(hasPrintContent).toBeTruthy();

    // Tutup popup
    await newPage.close();
  });

  test('fix: Cetak buku induk penduduk dengan filter tahun dan bulan dipilih', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10722',
    },
  }, async ({ page }) => {
    // 1. Masuk ke modul bumindes_penduduk_induk
    await page.goto('bumindes_penduduk_induk');
    await expect(page.getByRole('heading', { name: 'Buku Induk Penduduk' })).toBeVisible();

    // 2. Pilih tahun
    const selectTahun = page.locator('#tahun');
    await expect(selectTahun).toBeVisible();
    
    // Pilih tahun yang tersedia (tahun saat ini atau tahun pertama yang ada)
    const currentYear = new Date().getFullYear().toString();
    await selectTahun.selectOption(currentYear);

    // Tunggu sebentar untuk parent bulan muncul
    await page.waitForTimeout(500);

    // Pastikan select bulan muncul setelah tahun dipilih
    const selectBulan = page.locator('#bulan');
    const isVisible = await selectBulan.evaluate(el => {
      const parent = el.parentElement;
      return parent ? window.getComputedStyle(parent).display !== 'none' : false;
    });
    expect(isVisible).toBeTruthy();

    // Pilih bulan
    await selectBulan.selectOption('1'); // Januari

    // 3. Klik tombol Cetak/Unduh
    const cetakUnduhButton = page.getByRole('button', { name: /Cetak\/Unduh/i });
    await expect(cetakUnduhButton).toBeVisible();
    await cetakUnduhButton.click();

    // 4. Pilih menu Cetak dari dropdown
    const cetakLink = page.locator('a[href*="bumindes_penduduk_induk/dialog/cetak"]').first();
    await expect(cetakLink).toBeVisible();
    
    // Listen untuk modal dialog
    const modalPromise = page.waitForSelector('#modalBox .modal-dialog', { timeout: 5000 });
    await cetakLink.click();
    await modalPromise;

    // Tunggu modal dialog cetak muncul
    await expect(page.locator('#modalBox')).toBeVisible();
    
    // Klik tombol cetak di modal
    const submitButton = page.locator('#modalBox button[type="submit"]').first();
    await expect(submitButton).toBeVisible();
    
    // Listen untuk popup window baru
    const [newPage] = await Promise.all([
      page.waitForEvent('popup'),
      submitButton.click()
    ]);

    // 5. Tunggu halaman cetak terbuka dan verifikasi tidak ada error 500
    await newPage.waitForLoadState('networkidle');
    
    // Cek tidak ada error 500
    const pageContent = await newPage.content();
    expect(pageContent).not.toContain('HTTP ERROR 500');
    expect(pageContent).not.toContain('500 Internal Server Error');
    
    // Verifikasi halaman cetak berhasil dimuat
    await expect(newPage.locator('body')).toBeVisible();
    
    // Verifikasi ada konten yang expected di halaman cetak dengan bulan dan tahun
    const hasPrintContent = await newPage.locator('.judul, table').count() > 0;
    expect(hasPrintContent).toBeTruthy();

    // Verifikasi judul mengandung tahun yang dipilih
    const hasYearInTitle = await newPage.locator('h4').allTextContents();
    const titleText = hasYearInTitle.join(' ');
    expect(titleText).toContain(currentYear);

    // Tutup popup
    await newPage.close();
  });
});
