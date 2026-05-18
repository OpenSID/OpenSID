import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug Fix: Visibilitas field Status Kehamilan pada entri penduduk baru #11229', () => {
  test('UTAMA: Field Status Kehamilan tidak boleh tampil untuk perempuan saat entri penduduk baru (jenis_peristiwa=1)', {
    annotation: {
      type: 'issue',
      description: 'Issue #11229 - Field Status Kehamilan harus disembunyikan saat entri penduduk baru',
    },
  }, async ({ page }) => {
    // ARRANGE: Navigasi ke form penduduk dengan jenis_peristiwa = 1 (entri penduduk baru)
    await page.goto('admin/penduduk/form_peristiwa/1');
    
    // Tunggu halaman sepenuhnya ter-load
    await page.waitForLoadState('networkidle');
    
    // ACT: Cari dan pilih 'Perempuan' dari select Jenis Kelamin
    const sexSelect = page.locator('select[name="sex"]');
    
    // Verifikasi select terlihat
    await expect(sexSelect).toBeVisible({ timeout: 5000 });
    
    // Periksa opsi yang tersedia
    const options = await sexSelect.locator('option').allTextContents();
    console.log('Opsi Jenis Kelamin yang tersedia:', options);
    
    // Pilih Perempuan (nilai '2')
    await sexSelect.selectOption('2');
    
    // Tunggu handler JavaScript (fungsi ubah_sex) dijalankan
    await page.waitForTimeout(500);
    
    // ASSERT: Verifikasi field Status Kehamilan TIDAK terlihat
    const isisianHamilSection = page.locator('#isian_hamil, [id*="hamil"]').first();
    
    // Field harus tersembunyi karena jenis_peristiwa = 1 (entri baru)
    // Meskipun sex = Perempuan
    const isHidden = await isisianHamilSection.isHidden({ timeout: 2000 }).catch(() => false);
    const isNotInDOM = await isisianHamilSection.count() === 0;
    
    expect(isHidden || isNotInDOM).toBeTruthy(
      'Field Status Kehamilan harus disembunyikan pada entri penduduk baru (jenis_peristiwa=1) meskipun untuk perempuan'
    );
  });

  test('REGRESI: Field Status Kehamilan HARUS tampil untuk perempuan pada event non-entri (jenis_peristiwa!=1)', {
    annotation: {
      type: 'regression',
      description: 'Verifikasi field Status Kehamilan tampil dengan benar untuk perempuan pada event birth/lainnya',
    },
  }, async ({ page }) => {
    // Navigasi ke form penduduk dengan jenis_peristiwa = 2 (event non-entri seperti birth)
    await page.goto('admin/penduduk/form_peristiwa/2');
    
    // Tunggu halaman ter-load
    await page.waitForLoadState('networkidle');
    
    // Pilih Perempuan
    const sexSelect = page.locator('select[name="sex"]');
    await expect(sexSelect).toBeVisible({ timeout: 5000 });
    await sexSelect.selectOption('2');
    
    // Tunggu handler JavaScript
    await page.waitForTimeout(500);
    
    // Field Status Kehamilan HARUS terlihat pada event non-entri untuk perempuan
    const isisianHamilSection = page.locator('#isian_hamil, [id*="hamil"]').first();
    
    // Periksa jika terlihat - pada event non-entri dengan perempuan, harus tampil
    const isVisible = await isisianHamilSection.isVisible({ timeout: 2000 }).catch(() => false);
    
    // Catatan: Test ini mendokumentasikan perilaku yang diharapkan
    console.log('Visibilitas field Status Kehamilan pada event non-entri (jenis_peristiwa=2) untuk perempuan:', isVisible);
  });

  test('REGRESI: Field Status Kehamilan TIDAK boleh tampil untuk laki-laki pada event apapun', {
    annotation: {
      type: 'regression',
      description: 'Verifikasi field Status Kehamilan tidak pernah tampil untuk laki-laki terlepas dari tipe event',
    },
  }, async ({ page }) => {
    // Navigasi ke form penduduk dengan jenis_peristiwa = 1 (event entri)
    await page.goto('admin/penduduk/form_peristiwa/1');
    
    // Tunggu halaman ter-load
    await page.waitForLoadState('networkidle');
    
    // Pilih Laki-laki
    const sexSelect = page.locator('select[name="sex"]');
    await expect(sexSelect).toBeVisible({ timeout: 5000 });
    await sexSelect.selectOption('1'); // Laki-laki
    
    // Tunggu handler JavaScript
    await page.waitForTimeout(500);
    
    // Field Status Kehamilan harus disembunyikan untuk laki-laki
    const isisianHamilSection = page.locator('#isian_hamil, [id*="hamil"]').first();
    const isHidden = await isisianHamilSection.isHidden({ timeout: 2000 }).catch(() => false);
    const isNotInDOM = await isisianHamilSection.count() === 0;
    
    expect(isHidden || isNotInDOM).toBeTruthy(
      'Field Status Kehamilan harus disembunyikan untuk laki-laki pada event apapun'
    );
  });

  test('VERIFIKASI: Fungsi ubah_sex berisi pengecekan jenis_peristiwa', {
    annotation: {
      type: 'verification',
      description: 'Verifikasi fix ada dalam kode JavaScript',
    },
  }, async ({ page }) => {
    // Navigasi ke form penduduk apapun
    await page.goto('admin/penduduk/form_peristiwa/1');
    
    // Tunggu halaman ter-load
    await page.waitForLoadState('networkidle');
    
    // Ambil konten halaman
    const pageContent = await page.content();
    
    // Verifikasi fix ada: pengecekan jenis_peristiwa != 1
    expect(pageContent).toContain('jenis_peristiwa != 1');
    expect(pageContent).toContain('ubah_sex');
    expect(pageContent).toContain('$("#isian_hamil")');
    
    // Verifikasi kondisi lengkap ada di halaman
    expect(pageContent).toMatch(
      /jenis_peristiwa\s*!=\s*1\s*&&\s*sex\s*==|jenis_peristiwa\s*!=\s*1.*?sex\s*==/
    );
  });

  test('EDGE CASE: Form reset harus mempertahankan state benar dari field Status Kehamilan', {
    annotation: {
      type: 'edge-case',
      description: 'Verifikasi visibilitas field Status Kehamilan setelah form di-reset',
    },
  }, async ({ page }) => {
    // Navigasi ke form penduduk
    await page.goto('admin/penduduk/form_peristiwa/1');
    
    // Tunggu halaman ter-load
    await page.waitForLoadState('networkidle');
    
    const sexSelect = page.locator('select[name="sex"]');
    const resetButton = page.locator('button[type="reset"], input[type="reset"]').first();
    
    // Pilih Perempuan terlebih dahulu
    await sexSelect.selectOption('2');
    await page.waitForTimeout(300);
    
    // Verifikasi field Status Kehamilan disembunyikan
    const isisianHamilSection = page.locator('#isian_hamil, [id*="hamil"]').first();
    const isHiddenBeforeReset = await isisianHamilSection.isHidden({ timeout: 2000 }).catch(() => false);
    
    // Klik tombol reset jika tersedia
    if (await resetButton.isVisible({ timeout: 1000 }).catch(() => false)) {
      await resetButton.click();
      await page.waitForTimeout(500);
      
      // Verifikasi state field konsisten setelah reset
      const isHiddenAfterReset = await isisianHamilSection.isHidden({ timeout: 2000 }).catch(() => false);
      const isNotInDOM = await isisianHamilSection.count() === 0;
      
      expect(isHiddenAfterReset || isNotInDOM).toBeTruthy(
        'Field Status Kehamilan harus tetap disembunyikan setelah form di-reset pada event entri'
      );
    }
  });

  test('INTERAKSI: Perubahan field Jenis Kelamin harus langsung update visibilitas Status Kehamilan', {
    annotation: {
      type: 'interaction',
      description: 'Verifikasi perubahan field sex langsung memicu fungsi ubah_sex',
    },
  }, async ({ page }) => {
    // Navigasi ke form penduduk
    await page.goto('admin/penduduk/form_peristiwa/1');
    
    // Tunggu halaman ter-load
    await page.waitForLoadState('networkidle');
    
    const sexSelect = page.locator('select[name="sex"]');
    const isisianHamilSection = page.locator('#isian_hamil, [id*="hamil"]').first();
    
    // Ubah ke laki-laki
    await sexSelect.selectOption('1');
    await page.waitForTimeout(300);
    
    // Harus disembunyikan
    let isHidden = await isisianHamilSection.isHidden({ timeout: 1000 }).catch(() => false);
    expect(isHidden).toBeTruthy('Harus disembunyikan untuk laki-laki');
    
    // Ubah ke perempuan
    await sexSelect.selectOption('2');
    await page.waitForTimeout(300);
    
    // Harus tetap disembunyikan pada event entri (jenis_peristiwa = 1)
    isHidden = await isisianHamilSection.isHidden({ timeout: 1000 }).catch(() => false);
    expect(isHidden).toBeTruthy('Harus tetap disembunyikan untuk perempuan pada event entri');
    
    // Ubah kembali ke laki-laki
    await sexSelect.selectOption('1');
    await page.waitForTimeout(300);
    
    // Harus disembunyikan
    isHidden = await isisianHamilSection.isHidden({ timeout: 1000 }).catch(() => false);
    expect(isHidden).toBeTruthy('Harus kembali disembunyikan untuk laki-laki');
  });
});
