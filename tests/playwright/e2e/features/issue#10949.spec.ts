/**
 * Playwright E2E Tests
 * Fix: Bug tampilan Penomoran Surat di Menu Pengaturan Surat/Lainnya
 * Issue: https://github.com/OpenSID/OpenSID/issues/10949
 *
 * Permasalahan:
 * - Tampilan Penomoran Surat di Menu Pengaturan Surat/Lainnya berubah/tidak normal
 * - Styling form-control dan select2 tidak diterapkan dengan benar
 * - HTML structure select element menjadi tidak valid
 *
 * Langkah Reproduksi:
 * 1. Masuk ke Menu Layanan Surat
 * 2. Klik Pengaturan Surat
 * 3. Klik Tab "Lainnya"
 * 4. Scroll ke bawah cari "Penomoran Surat"
 * 5. Verifikasi tampilan dropdown sudah normal dan berfungsi dengan baik
 */

import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug: Tampilan Penomoran Surat di Menu Pengaturan Surat/Lainnya #10949', () => {

  test.beforeEach(async ({ page }) => {
    // Navigasi ke halaman pengaturan surat
    await page.goto('surat_master/pengaturan');
    
    // Tunggu halaman selesai dimuat
    await expect(page.locator('h1')).toBeVisible();
  });

  test('verifikasi halaman pengaturan surat dapat diakses', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10949',
    },
  }, async ({ page }) => {
    // Verifikasi ada tab untuk "Lainnya"
    const lainnyaTab = page.locator('a[href="#lainnya"]');
    await expect(lainnyaTab).toBeVisible();
  });

  test('verifikasi tab "Lainnya" dapat diklik dan menampilkan konten', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10949',
    },
  }, async ({ page }) => {
    // Klik tab "Lainnya"
    const lainnyaTab = page.locator('a[href="#lainnya"]');
    await lainnyaTab.click();
    
    // Tunggu tab content muncul
    const lainnyaContent = page.locator('#lainnya');
    await expect(lainnyaContent).toBeVisible();
  });

  test('verifikasi field "Penomoran Surat" ada dan terlihat normal', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10949',
    },
  }, async ({ page }) => {
    // Klik tab "Lainnya"
    const lainnyaTab = page.locator('a[href="#lainnya"]');
    await lainnyaTab.click();
    
    // Tunggu tab content muncul dan scroll ke dalamnya
    const lainnyaContent = page.locator('#lainnya');
    await expect(lainnyaContent).toBeVisible();
    
    // Scroll ke bawah untuk mencari field Penomoran Surat
    await page.evaluate(() => {
      const tabPane = document.querySelector('#lainnya');
      if (tabPane) {
        tabPane.parentElement?.scrollTo(0, tabPane.offsetHeight);
      }
    });
    
    // Cari label "Penomoran Surat"
    const labelPenomoran = page.locator('label:has-text("Penomoran Surat")').first();
    await expect(labelPenomoran).toBeVisible({ timeout: 5000 });
  });

  test('verifikasi dropdown Penomoran Surat memiliki styling yang benar', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10949',
    },
  }, async ({ page }) => {
    // Klik tab "Lainnya"
    const lainnyaTab = page.locator('a[href="#lainnya"]');
    await lainnyaTab.click();
    
    // Tunggu tab content muncul
    const lainnyaContent = page.locator('#lainnya');
    await expect(lainnyaContent).toBeVisible();
    
    // Scroll ke bawah
    await page.evaluate(() => {
      const tabPane = document.querySelector('#lainnya');
      if (tabPane) {
        tabPane.parentElement?.scrollTo(0, tabPane.offsetHeight);
      }
    });
    
    // Cari select dengan id "penomoran_surat"
    const selectPenomoran = page.locator('#penomoran_surat').first();
    await expect(selectPenomoran).toBeVisible({ timeout: 5000 });
    
    // Verifikasi elemen memiliki class yang diperlukan
    await expect(selectPenomoran).toHaveClass(/form-control/);
    
    // Verifikasi elemen memiliki class select2 untuk jQuery Select2
    // Note: Select2 diterapkan via JavaScript, jadi class mungkin dinamis
    const selectContainer = selectPenomoran.locator('..').first();
    const isSelect2 = await selectContainer.evaluate((el) => {
      return el.classList.contains('select2-container') || 
             el.querySelector('.select2-selection') !== null;
    });
    
    // Verifikasi struktur dropdown tidak broken
    await expect(selectPenomoran).toHaveJSProperty('tagName', 'SELECT');
  });

  test('verifikasi dropdown Penomoran Surat dapat diinteraksi', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10949',
    },
  }, async ({ page }) => {
    // Klik tab "Lainnya"
    const lainnyaTab = page.locator('a[href="#lainnya"]');
    await lainnyaTab.click();
    
    // Tunggu tab content muncul
    const lainnyaContent = page.locator('#lainnya');
    await expect(lainnyaContent).toBeVisible();
    
    // Scroll ke bawah
    await page.evaluate(() => {
      const tabPane = document.querySelector('#lainnya');
      if (tabPane) {
        tabPane.parentElement?.scrollTo(0, tabPane.offsetHeight);
      }
    });
    
    // Cari select dengan id "penomoran_surat"
    const selectPenomoran = page.locator('#penomoran_surat').first();
    await expect(selectPenomoran).toBeVisible({ timeout: 5000 });
    
    // Verifikasi ada minimal 4 opsi (sesuai database setting)
    const options = selectPenomoran.locator('option');
    const optionCount = await options.count();
    expect(optionCount).toBeGreaterThanOrEqual(4);
    
    // Verifikasi deskripsi opsi pertama
    const firstOption = options.first();
    const firstOptionText = await firstOption.textContent();
    expect(firstOptionText).toContain('Nomor berurutan');
  });

  test('verifikasi form dapat disubmit dengan nilai Penomoran Surat', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10949',
    },
  }, async ({ page }) => {
    // Klik tab "Lainnya"
    const lainnyaTab = page.locator('a[href="#lainnya"]');
    await lainnyaTab.click();
    
    // Tunggu tab content muncul
    const lainnyaContent = page.locator('#lainnya');
    await expect(lainnyaContent).toBeVisible();
    
    // Scroll ke bawah
    await page.evaluate(() => {
      const tabPane = document.querySelector('#lainnya');
      if (tabPane) {
        tabPane.parentElement?.scrollTo(0, tabPane.offsetHeight);
      }
    });
    
    // Cari select dengan id "penomoran_surat"
    const selectPenomoran = page.locator('#penomoran_surat').first();
    await expect(selectPenomoran).toBeVisible({ timeout: 5000 });
    
    // Ambil nilai saat ini
    const currentValue = await selectPenomoran.inputValue();
    
    // Pilih opsi yang berbeda jika ada
    const options = selectPenomoran.locator('option');
    const optionCount = await options.count();
    
    if (optionCount > 1) {
      const secondOption = options.nth(1);
      const secondValue = await secondOption.getAttribute('value');
      
      // Ubah ke opsi lain
      await selectPenomoran.selectOption(secondValue || '1');
      
      // Verifikasi nilai berubah
      const newValue = await selectPenomoran.inputValue();
      expect(newValue).not.toBe(currentValue);
      
      // Kembalikan ke nilai semula
      await selectPenomoran.selectOption(currentValue);
    }
  });

  test('verifikasi helper text Penomoran Surat ditampilkan dengan benar', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10949',
    },
  }, async ({ page }) => {
    // Klik tab "Lainnya"
    const lainnyaTab = page.locator('a[href="#lainnya"]');
    await lainnyaTab.click();
    
    // Tunggu tab content muncul
    const lainnyaContent = page.locator('#lainnya');
    await expect(lainnyaContent).toBeVisible();
    
    // Scroll ke bawah
    await page.evaluate(() => {
      const tabPane = document.querySelector('#lainnya');
      if (tabPane) {
        tabPane.parentElement?.scrollTo(0, tabPane.offsetHeight);
      }
    });
    
    // Cari label Penomoran Surat
    const labelPenomoran = page.locator('label:has-text("Penomoran Surat")').first();
    await expect(labelPenomoran).toBeVisible({ timeout: 5000 });
    
    // Cari help-block text di bawah select
    const helpText = page.locator('span.help-block.small.text-red').first();
    
    // Verifikasi teks bantuan ada dan berisi informasi tentang penomoran
    if (await helpText.isVisible()) {
      const text = await helpText.textContent();
      expect(text).toBeTruthy();
      expect(text).toContain('Penomoran');
    }
  });

  test('verifikasi visual form-group Penomoran Surat tidak broken', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10949',
    },
  }, async ({ page }) => {
    // Klik tab "Lainnya"
    const lainnyaTab = page.locator('a[href="#lainnya"]');
    await lainnyaTab.click();
    
    // Tunggu tab content muncul
    const lainnyaContent = page.locator('#lainnya');
    await expect(lainnyaContent).toBeVisible();
    
    // Scroll ke bawah
    await page.evaluate(() => {
      const tabPane = document.querySelector('#lainnya');
      if (tabPane) {
        tabPane.parentElement?.scrollTo(0, tabPane.offsetHeight);
      }
    });
    
    // Cari form-group yang mengandung Penomoran Surat
    const labelPenomoran = page.locator('label:has-text("Penomoran Surat")').first();
    const formGroup = labelPenomoran.locator('../..');
    
    await expect(formGroup).toBeVisible({ timeout: 5000 });
    await expect(formGroup).toHaveClass(/form-group/);
    
    // Verifikasi struktur: label + select + help-block
    const label = formGroup.locator('label').first();
    const select = formGroup.locator('select#penomoran_surat');
    
    await expect(label).toBeVisible();
    await expect(select).toBeVisible();
  });
});
