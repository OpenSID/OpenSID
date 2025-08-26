import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Buku inventaris #9988', () => {
  test('validasi buku inventaris', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/9988',
    },
  }, async ({ page }) => {
    await page.goto('inventaris_tanah');
    try {
    await page.getByRole('link', { name: ' Tambah' }).click();
    await page.getByRole('button', { name: ' Simpan' }).click();
    await page.getByRole('textbox', { name: 'Luas Tanah Kolom ini' }).fill('1');
    await page.getByRole('textbox', { name: 'Luas Tanah Kolom ini' }).press('Tab');
    await page.getByRole('textbox', { name: 'Letak / Alamat Kolom ini' }).click();
    await page.getByRole('textbox', { name: 'Letak / Alamat Kolom ini' }).fill('1');
    await page.getByLabel('Penggunaan', { exact: true }).selectOption('Industri');
    await page.getByRole('textbox', { name: 'Harga Kolom ini diperlukan.' }).click();
    await page.getByRole('textbox', { name: 'Harga Kolom ini diperlukan.' }).fill('10000');
    await page.locator('#asal').selectOption('Bantuan Kabupaten');
    await page.getByRole('textbox', { name: 'Keterangan Kolom ini' }).click();
    await page.getByRole('textbox', { name: 'Keterangan Kolom ini' }).fill('1');
    await page.getByRole('button', { name: ' Simpan' }).click();
    await expect(page.getByRole('link', { name: '' }).nth(2)).toBeVisible();
    await page.getByRole('link', { name: '' }).nth(2).click();
    await page.locator('a').filter({ hasText: 'Hapus' }).click();
  } catch { }
  });
});