import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Notifikasi Ubah Status #9908', () => {
  test('fix: perbaiki Notifikasi Ubah Status dasar', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/9908',
    },
  }, async ({ page }) => {
    await page.goto('penduduk');
    await page.getByRole('row', { name: '1  Pilih Aksi Foto Penduduk' }).getByRole('button').click();
    await page.getByRole('link', { name: ' Ubah Status Dasar' }).click();
    await page.locator('#modalBox').getByText('Ubah Status Dasar').click();
    await page.getByTitle('Pilih Status Dasar').click();
    await page.getByRole('treeitem', { name: 'Mati' }).click();
    const tempatMeninggal = page.getByPlaceholder('Tempat Meninggal');
    await expect(tempatMeninggal).toBeVisible();
    await tempatMeninggal.click();
    await page.getByPlaceholder('Tempat Meninggal').fill('bauru');
    await page.waitForSelector('[placeholder="Jam Kematian"]');
    await page.getByPlaceholder('Jam Kematian').click();
    await page.getByTitle('Pick Hour').click();
    await page.getByTitle('Pilih Penyebab Kematian').click();
    await page.getByRole('treeitem', { name: 'Sakit biasa / tua' }).click();
    await page.getByTitle('Pilih Yang Menerangkan').click();
    await page.getByRole('treeitem', { name: 'Dokter' }).click();
    await page.waitForSelector('[placeholder="Nomor Akta Kematian"]');
    await page.getByPlaceholder('Nomor Akta Kematian').click();
    await page.getByPlaceholder('Nomor Akta Kematian').fill('2345678');
    await page.locator('#file_path').click();
    await page.getByText('Cari', { exact: true }).click();
    await page.waitForSelector('[placeholder="Catatan"]');
    await page.getByPlaceholder('Catatan').click();
    await page.getByPlaceholder('Catatan').fill('dfg');
    await page.locator('#ok').click();
    await expect(page.locator('#notifikasi')).toContainText('Status dasar penduduk berhasil diubah');
  });
});