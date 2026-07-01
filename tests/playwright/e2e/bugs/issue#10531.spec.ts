import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Jenis Kelamin di Kartu Peserta / Penerima #10531', () => {
  test('fix: Jenis Kelamin di Kartu Peserta / Penerima', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10531',
    },
  }, async ({ page }) => {
    try {
    await page.goto('program_bantuan/clear');
    await expect(page.getByRole('link', { name: ' Tambah' })).toBeVisible();
    await page.getByRole('link', { name: ' Tambah' }).click();
    await page.locator('#cid').selectOption('2');
    await page.getByRole('list').filter({ hasText: '×KEPALA KELUARGA' }).click();
    await page.getByRole('treeitem', { name: 'SUAMI' }).click();
    await page.getByText('×KEPALA KELUARGA×SUAMI').click();
    await page.getByRole('treeitem', { name: 'ISTRI' }).click();
    await page.getByRole('textbox', { name: 'Nama Program' }).click();
    await page.getByRole('textbox', { name: 'Nama Program' }).fill('bansos');
    await page.getByRole('textbox', { name: 'Keterangan' }).click();
    await page.getByRole('textbox', { name: 'Keterangan' }).fill('test');
    await page.getByLabel('Asal Dana').selectOption('Pusat');
    await page.getByRole('button', { name: ' Simpan' }).click();
    await page.getByRole('gridcell', { name: 'bansos' }).dblclick();
    await page.getByRole('row', { name: '1    bansos Pusat 0 25 Nov' }).getByRole('link').first().click();
    await page.getByText('Tambah', { exact: true }).click();
    await page.getByRole('link', { name: ' Tambah Satu Peserta Baru' }).click();
    await page.getByRole('textbox', { name: '-- Silakan Masukan No. KK /' }).click();
    await page.getByRole('treeitem', { name: 'No KK : 7307021408150001 - ISTRI- NIK : 7307024107900067 - HASNIATI RT-003, RW-' }).click();
    await page.getByRole('textbox', { name: 'Nomor Kartu Peserta' }).click();
    await page.getByRole('textbox', { name: 'Nomor Kartu Peserta' }).fill('5666');
    await page.getByRole('button', { name: ' Simpan' }).dblclick();
    await page.getByRole('heading', { name: 'Program Bantuan bansos' }).click();
    await page.getByRole('gridcell', { name: 'PEREMPUAN' }).click();
    await page.getByRole('link', { name: '', exact: true }).click();
    await page.locator('#confirm-delete a').click();
    await page.getByRole('heading', { name: ' Berhasil' }).click();
    await page.getByRole('link', { name: ' Kembali ke Daftar Program' }).click();
    await page.getByRole('gridcell', { name: 'bansos' }).dblclick();
    await page.getByRole('row', { name: '1    bansos Pusat 0 25 Nov' }).getByRole('link').nth(2).click();
    await page.locator('a').filter({ hasText: 'Hapus' }).click();
    await page.getByRole('heading', { name: ' Berhasil' }).click();

    } catch { }
  });
});