import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Kategori ENUM pada pengaturan peta tidak konsisten #10641', () => {
  test('fix: perbaiki proses tambah dan ubah jenis dan kategori pada pengaturan peta', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10641',
    },
  }, async ({ page }) => {
    await page.goto('garis');
    await page.getByRole('textbox', { name: 'Nama pengguna' }).click();
    await page.getByRole('textbox', { name: 'Nama pengguna' }).fill('admin');
    await page.getByRole('textbox', { name: 'Kata sandi' }).click();
    await page.getByRole('textbox', { name: 'Kata sandi' }).fill('sid304');
    await page.getByRole('button', { name: 'Masuk', exact: true }).click();
    await page.goto('garis');
    await page.getByRole('link', { name: ' Tambah' }).click();
    await page.locator('input[name="nama"]').click();
    await page.locator('input[name="nama"]').fill('cek');
    await page.getByRole('textbox', { name: 'Pilih Jenis' }).click();
    await page.getByRole('treeitem', { name: 'Jalan', exact: true }).click();
    await page.getByRole('textbox', { name: 'Pilih Kategori' }).click();
    await page.getByRole('treeitem', { name: 'Jalan Raya' }).click();
    await page.locator('#desk').click();
    await page.locator('#desk').fill('cek');
    await page.getByLabel('Status').selectOption('1');
    await page.getByRole('button', { name: ' Simpan' }).click();
  });
});
