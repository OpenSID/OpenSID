import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Kolom nama penduduk terhapus namun data masih muncul di tabel ketika klik batal pada "Tambah Anggota Rumah Tangga" #9990', () => {
  test('fix: Button reset form tambah anggota', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/9990',
    },
  }, async ({ page }) => {
    await page.goto('rtm');
    await expect(page.getByRole('row', { name: '1      Foto Penduduk' }).getByRole('link').nth(1)).toBeVisible();
    await page.getByRole('row', { name: '1      Foto Penduduk' }).getByRole('link').nth(1).click();
    await page.getByText('Tambah Anggota Rumah Tangga').click();
    await page.getByText('×-- Silakan Cari NIK / Nama').click();
    await page.getByRole('treeitem', { name: 'NIK : 3324032107750001 -' }).click();
    await page.getByLabel('No: aktifkan untuk').click();
    await page.locator('tr').filter({ hasText: '23324026108860001NURUL' }).locator('input[name="id_cb\\[\\]"]').check();
    await page.locator('#validasi').getByText('Batal').click();
    await page.getByText('×-- Silakan Cari NIK / Nama').click();
  });
});