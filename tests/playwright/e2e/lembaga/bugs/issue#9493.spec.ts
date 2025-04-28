import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../../storage/auth/admin.json'),
});

test.describe('Link "Daftar Lembaga" pada breadcrumb laman rincian #9493', () => {
  test('fix: perbaiki link breadcumb detail lembaga', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/9493',
    },
  }, async ({ page }) => {
    await page.goto('lembaga');
    await page.getByRole('link', { name: ' Tambah' }).click();
    await page.getByRole('textbox', { name: 'Nama Lembaga' }).click();
    await page.getByRole('textbox', { name: 'Nama Lembaga' }).press('CapsLock');
    await page.getByRole('textbox', { name: 'Nama Lembaga' }).fill('T');
    await page.getByRole('textbox', { name: 'Nama Lembaga' }).press('CapsLock');
    await page.getByRole('textbox', { name: 'Nama Lembaga' }).fill('Test ');
    await page.getByRole('textbox', { name: 'Nama Lembaga' }).press('CapsLock');
    await page.getByRole('textbox', { name: 'Nama Lembaga' }).fill('Test L');
    await page.getByRole('textbox', { name: 'Nama Lembaga' }).press('CapsLock');
    await page.getByRole('textbox', { name: 'Nama Lembaga' }).fill('Test Lembaga');
    await page.getByRole('textbox', { name: 'Kode Lembaga No. SK Pendirian' }).click();
    await page.getByRole('textbox', { name: 'Kode Lembaga No. SK Pendirian' }).fill('123123');
    await page.getByRole('textbox', { name: 'No. SK Pendirian Lembaga', exact: true }).click();
    await page.getByRole('textbox', { name: 'No. SK Pendirian Lembaga', exact: true }).fill('123123');
    await page.getByRole('textbox', { name: '-- Silakan Masukkan Kategori' }).click();
    await page.getByRole('treeitem', { name: 'lembaga &' }).click();
    await page.getByRole('textbox', { name: '-- Silakan Masukkan NIK /' }).click();
    await page.getByRole('treeitem', { name: 'NIK :1505022308730003 -' }).click();
    await page.getByRole('textbox', { name: 'Deskripsi Lembaga' }).click();
    await page.getByRole('textbox', { name: 'Deskripsi Lembaga' }).fill('test');
    await page.getByRole('button', { name: ' Simpan' }).click();
    await page.getByRole('link', { name: '' }).click();
    await page.getByRole('link', { name: 'Daftar Lembaga', exact: true }).click();
    await expect(page.getByRole('heading', { name: 'Pengelolaan Lembaga' })).toBeVisible();
  });
});
