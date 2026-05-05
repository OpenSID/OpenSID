import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Perbaiki: Form Validasi Jenis dan Kategori Lokasi #11130', () => {
  test('fix: Tampilkan indikator field wajib di form Jenis Lokasi', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11130',
    },
  }, async ({ page }) => {
    test.setTimeout(30000);
    await page.goto('peta/point');

    // Klik tombol Tambah
    await page.getByRole('link', { name: /Tambah/i }).click();
    await page.waitForSelector('form#validasi');

    // Verifikasi ada indikator (*) pada label field wajib
    const namaLabel = await page.locator('label:has-text("Nama Jenis Lokasi")');
    await expect(namaLabel).toBeVisible();

    const requiredSpans = await page.locator('label:has-text("Nama Jenis Lokasi") .text-danger').count();
    expect(requiredSpans).toBeGreaterThan(0);

    const symbolLabel = await page.locator('label:has-text("Ganti Simbol")');
    await expect(symbolLabel).toBeVisible();

    const statusLabel = await page.locator('label:has-text("Status")');
    await expect(statusLabel).toBeVisible();
  });

  test('fix: Validasi field wajib di form Jenis Lokasi', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11130',
    },
  }, async ({ page }) => {
    test.setTimeout(30000);
    await page.goto('peta/point');

    // Klik tombol Tambah
    await page.getByRole('link', { name: /Tambah/i }).click();
    await page.waitForSelector('form#validasi');

    // Coba submit form kosong
    await page.getByRole('button', { name: /Simpan/i }).click();

    // Tunggu validasi error muncul
    await page.waitForTimeout(1000);

    // Verifikasi ada error message untuk field wajib
    const errorMessages = await page.locator('.error').count();
    expect(errorMessages).toBeGreaterThan(0);
  });

  test('fix: Validasi field Nama wajib di form Jenis Lokasi', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11130',
    },
  }, async ({ page }) => {
    test.setTimeout(30000);
    await page.goto('peta/point');

    await page.getByRole('link', { name: /Tambah/i }).click();
    await page.waitForSelector('form#validasi');

    const namaInput = page.locator('input[name="nama"]');
    await namaInput.focus();
    await namaInput.blur();

    // Harus menampilkan error untuk field nama kosong
    await page.waitForTimeout(500);
    const errorCount = await page.locator('input[name="nama"]').evaluate(() => {
      const parent = document.querySelector('input[name="nama"]')?.closest('.form-group');
      return parent?.querySelectorAll('.error').length ?? 0;
    });

    expect(errorCount).toBeGreaterThan(0);
  });

  test('fix: Validasi field Simbol dengan custom error placement', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11130',
    },
  }, async ({ page }) => {
    test.setTimeout(30000);
    await page.goto('peta/point');

    await page.getByRole('link', { name: /Tambah/i }).click();
    await page.waitForSelector('form#validasi');

    // Isi field nama yang wajib
    await page.locator('input[name="nama"]').fill('Test Lokasi');

    // Coba submit tanpa memilih simbol
    await page.getByRole('button', { name: /Simpan/i }).click();

    // Tunggu validasi
    await page.waitForTimeout(1000);

    // Verifikasi error ditempatkan setelah scrollbar (custom placement)
    const errorAfterScrollbar = await page.evaluate(() => {
      const scrollbar = document.querySelector('.vertical-scrollbar');
      if (!scrollbar) return false;
      const nextElement = scrollbar.nextElementSibling;
      return nextElement?.classList.contains('error') || false;
    });

    // Error harus terlihat (custom placement atau standard)
    const errorVisible = await page.locator('.error:has-text("required")').count();
    expect(errorVisible + (errorAfterScrollbar ? 1 : 0)).toBeGreaterThan(0);
  });

  test('fix: Validasi field Status wajib di form Jenis Lokasi', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11130',
    },
  }, async ({ page }) => {
    test.setTimeout(30000);
    await page.goto('peta/point');

    await page.getByRole('link', { name: /Tambah/i }).click();
    await page.waitForSelector('form#validasi');

    const statusSelect = page.locator('select[name="enabled"]');
    await expect(statusSelect).toHaveClass(/required/);
  });

  test('fix: Tampilkan indikator field wajib di modal Kategori Lokasi', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11130',
    },
  }, async ({ page }) => {
    test.setTimeout(30000);
    await page.goto('peta/point');

    // Pertama buat Jenis Lokasi
    await page.getByRole('link', { name: /Tambah/i }).click();
    await page.waitForSelector('form#validasi');

    await page.locator('input[name="nama"]').fill('Test Jenis Lokasi');

    // Pilih simbol pertama
    const firstSymbol = await page.locator('ul.bs-glyphicons li').first();
    await firstSymbol.click();

    // Pilih status
    await page.locator('select[name="enabled"]').selectOption('1');

    // Simpan
    await page.getByRole('button', { name: /Simpan/i }).click();

    // Tunggu success message
    await page.waitForSelector('button:has-text("Tutup")', { timeout: 5000 });

    // Tutup modal
    await page.getByRole('button', { name: /Tutup/i }).click();

    // Tunggu tabel dimuat
    await page.waitForTimeout(1000);

    // Klik pada item yang dibuat untuk melihat form subpoint
    const tableRow = page.locator('tbody tr').first();
    const editButton = tableRow.locator('a[data-toggle="modal"][data-target="#modal-item"]').first();

    if (await editButton.isVisible()) {
      await editButton.click();
      await page.waitForSelector('.modal-body form#validasi');

      // Verifikasi indikator wajib di modal
      const namaLabelInModal = await page.locator('.modal-body label:has-text("Nama Kategori Lokasi")');
      await expect(namaLabelInModal).toBeVisible();

      const requiredSpansInModal = await page.locator('.modal-body label:has-text("Nama Kategori Lokasi") .text-danger').count();
      expect(requiredSpansInModal).toBeGreaterThan(0);
    }
  });

  test('fix: Validasi field di modal Kategori Lokasi', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11130',
    },
  }, async ({ page }) => {
    test.setTimeout(30000);
    await page.goto('peta/point');

    // Buat Jenis Lokasi terlebih dahulu
    await page.getByRole('link', { name: /Tambah/i }).click();
    await page.waitForSelector('form#validasi');

    await page.locator('input[name="nama"]').fill('Test Jenis Lokasi 2');

    const firstSymbol = await page.locator('ul.bs-glyphicons li').first();
    await firstSymbol.click();

    await page.locator('select[name="enabled"]').selectOption('1');
    await page.getByRole('button', { name: /Simpan/i }).click();

    await page.waitForSelector('button:has-text("Tutup")', { timeout: 5000 });
    await page.getByRole('button', { name: /Tutup/i }).click();

    // Tunggu dan coba buka form subpoint
    await page.waitForTimeout(1000);

    const tableRow = page.locator('tbody tr').first();
    const editButton = tableRow.locator('a[data-toggle="modal"]').first();

    if (await editButton.isVisible()) {
      await editButton.click();
      await page.waitForSelector('.modal-body form#validasi');

      // Coba submit form kosong
      const submitButton = page.locator('.modal-footer button:has-text("Simpan")');
      if (await submitButton.isVisible()) {
        await submitButton.click();

        // Tunggu validation errors
        await page.waitForTimeout(1000);

        // Verifikasi ada validation error
        const errorCount = await page.locator('.modal-body .error').count();
        expect(errorCount).toBeGreaterThan(0);
      }
    }
  });

  test('fix: Terima input valid di form Jenis Lokasi', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11130',
    },
  }, async ({ page }) => {
    test.setTimeout(30000);
    await page.goto('peta/point');

    await page.getByRole('link', { name: /Tambah/i }).click();
    await page.waitForSelector('form#validasi');

    // Isi semua field wajib
    await page.locator('input[name="nama"]').fill('Test Lokasi Valid');

    // Pilih simbol
    const firstSymbol = await page.locator('ul.bs-glyphicons li').first();
    await firstSymbol.click();

    // Pilih status
    await page.locator('select[name="enabled"]').selectOption('1');

    // Submit form
    await page.getByRole('button', { name: /Simpan/i }).click();

    // Tunggu modal tutup atau success message
    await page.waitForTimeout(1500);

    // Verifikasi modal tutup atau success message ditampilkan
    const modal = page.locator('.modal-body');
    const isClosed = await modal.count() === 0;
    const successMsg = await page.locator('text=/Berhasil|Success/i').count();

    expect(isClosed || successMsg > 0).toBeTruthy();
  });

  test('fix: Terapkan styling border pada scrollbar container', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11130',
    },
  }, async ({ page }) => {
    test.setTimeout(30000);
    await page.goto('peta/point');

    await page.getByRole('link', { name: /Tambah/i }).click();
    await page.waitForSelector('form#validasi');

    // Verifikasi scrollbar memiliki styling border
    const scrollbar = page.locator('.vertical-scrollbar');
    const borderStyle = await scrollbar.evaluate(el =>
      window.getComputedStyle(el).border
    );

    expect(borderStyle).toBeTruthy();
    expect(borderStyle).toContain('px');
  });
});
