import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Race Condition pada Modul Artikel Admin #10849', () => {
  test('fix: perbaikan Race Condition pada Modul Artikel Admin', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10849',
    },
  }, async ({ page }) => {
    // Buka halaman daftar artikel
    await page.goto('web');

    // Klik tombol tambah artikel (ikon +)
    await page.getByRole('link', { name: '', exact: true }).click();

    // Pastikan halaman form muncul
    await expect(page.getByRole('textbox', { name: 'Judul Artikel' })).toBeVisible();

    // Isi judul artikel
    await page.getByRole('textbox', { name: 'Judul Artikel' })
      .fill('Testing Artikel Playwright');

    // Isi isi artikel (textarea TinyMCE)
    await page.locator('textarea[name="isi"]')
      .fill('Ini isi artikel untuk testing.');

    // Klik tombol simpan
    await page.getByRole('button', { name: 'Simpan' }).click();

    // Pastikan tidak ada error 500
    await expect(page).not.toHaveURL(/500/);

    // Pastikan redirect kembali ke daftar artikel
    await expect(page).toHaveURL(/web/);

  });
});
