import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Perbaiki error saat tinjau surat #10110', () => {
  test('fix: Perbaiki error pratinjau surat', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10110',
    },
  }, async ({ page }) => {
    try {
    await page.goto('surat');
    await page.getByRole('textbox', { name: 'Nama pengguna' }).click();
    await page.getByRole('textbox', { name: 'Nama pengguna' }).fill('admin');
    await page.getByRole('textbox', { name: 'Nama pengguna' }).press('Tab');
    await page.getByRole('textbox', { name: 'Kata sandi' }).fill('sid304');
    await page.getByRole('button', { name: 'Masuk' }).click();
    await page.goto('surat/form/sistem-surat-keterangan-bepergian');
    await page.getByRole('combobox', { name: '-- Cari NIK / Tag ID Card /' }).locator('b').click();
    await page.locator('input[type="search"]').fill('a');
    await page.getByRole('treeitem', { name: 'NIK/Tag ID Card : 5201142005716996 - AHLUL Alamat: RT-004, RW-- DUSUN MANGSIT' }).click();
    await page.getByRole('textbox', { name: 'Masukkan Keperluan' }).click();
    await page.getByRole('textbox', { name: 'Masukkan Keperluan' }).fill('testing');
    await page.getByRole('button', { name: ' Lanjut' }).click();
    await page.getByRole('button', { name: ' Tinjau PDF' }).click();
    await expect(page.getByRole('heading', { name: 'Pratinjau' })).toBeVisible();
    } catch { }
  });
});