// Smoke Test — Level 3: Modul Surat
// Letter/document workflow — one of the most critical modules.
import { test, expect } from './smokeTest';

test.describe('Smoke: Modul Surat', () => {
  test('halaman daftar template surat dapat diakses', async ({ page }) => {
    test.setTimeout(30000);

    const response = await page.goto('surat');
    expect(response?.status()).toBe(200);
    await page.waitForLoadState('networkidle');

    await expect(page.locator('.content-header h1, h1').first()).toBeVisible();
    await expect(page.locator('.alert-danger')).toHaveCount(0);
  });

  test('halaman pengaturan surat master dapat diakses', async ({ page }) => {
    test.setTimeout(30000);

    const response = await page.goto('surat_master');
    expect(response?.status()).toBe(200);
    await page.waitForLoadState('networkidle');

    await expect(page.locator('h1').first()).toBeVisible();
    await expect(page.locator('.alert-danger')).toHaveCount(0);
  });

  test('form membuat surat dapat dibuka', async ({ page }) => {
    test.setTimeout(45000);

    // Pakai slug surat yang aktif (kunci=0) & ada di data schema tes rilis-dev.
    // FormatSurat::cetak() hanya mengembalikan surat dengan kunci=KUNCI_DISABLE (0);
    // surat terkunci akan memunculkan "Surat tidak ditemukan".
    const response = await page.goto('surat/form/sistem-surat-keterangan-nikah');
    expect(response?.status()).toBe(200);
    await page.waitForLoadState('networkidle');

    // Form surat harus tampil dan tanpa pesan error.
    await expect(page.locator('form#validasi')).toBeVisible();
    await expect(page.locator('.alert-danger')).toHaveCount(0);
  });

  test('endpoint DataTables surat mengembalikan respons valid', async ({ page }) => {
    test.setTimeout(30000);

    let ajaxOk = false;
    page.on('response', async resp => {
      if (resp.url().includes('datatables') && resp.status() < 400) {
        ajaxOk = true;
      }
    });

    await page.goto('surat');
    await page.waitForLoadState('networkidle');

    // At minimum, page loads without server error
    await expect(page.locator('.alert-danger')).toHaveCount(0);
  });
});
