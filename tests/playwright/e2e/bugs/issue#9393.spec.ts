import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Ubah Kategori Tidak Tersedia #9393', () => {
  test('fix: perbaikan simpan jenis kategori dinamis dan statis', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/9393',
    },
  }, async ({ page }) => {
    await page.goto('web/keuangan');

    await page.getByRole('link', { name: ' Tambah Keuangan' }).click();
    await page.getByRole('textbox', { name: 'Judul Artikel' }).click();
    await page.getByRole('textbox', { name: 'Judul Artikel' }).fill('testt');
    await page.locator('iframe[title="Rich Text Area"]').contentFrame().getByRole('paragraph').click();
    await page.locator('iframe[title="Rich Text Area"]').contentFrame().locator('html').click();
    await page.locator('iframe[title="Rich Text Area"]').contentFrame().getByLabel('Rich Text Area').fill('testtt');
    await page.getByRole('button', { name: ' Simpan' }).click();
    await expect(page.locator('#notifikasi')).toContainText('Artikel berhasil ditambahkan');

    await page.getByRole('link', { name: '' }).first().click();
    await page.getByTitle('Keuangan').click();
    await page.getByRole('treeitem', { name: 'Statis' }).click();
    await page.getByText('Simpan', { exact: true }).click();
    await expect(page.getByRole('gridcell', { name: 'testt' })).toBeVisible();


    await page.getByRole('link', { name: '' }).nth(1).click();
    await page.locator('#confirm-delete a').click();
    await expect(page.getByText('Artikel berhasil dihapus')).toBeVisible();
  });
});
