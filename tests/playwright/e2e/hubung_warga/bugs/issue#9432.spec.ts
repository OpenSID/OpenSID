import { test, expect} from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../../storage/auth/admin.json'),
});

test.describe('Error Pada Pengiriman Pesan Grup #9432', () => {
  test('fix: perbaiki error hubung warga', {
    annotation: {
        type: 'issue',
        description: 'https://github.com/OpenSID/OpenSID/issues/9432'
    }
  }, async ({ page }) => {
    try {
    await page.goto('daftar_kontak');
    await page.getByRole('link', { name: ' Grup' }).click();
    await page.getByRole('link', { name: ' Tambah' }).click();
    await page.getByRole('textbox', { name: 'OpenDesa' }).click();
    await page.getByRole('textbox', { name: 'OpenDesa' }).fill('test grup');
    await page.getByRole('textbox', { name: 'Keterangan lainnya...' }).click();
    await page.getByRole('textbox', { name: 'Keterangan lainnya...' }).fill('test');
    await page.getByRole('button', { name: ' Simpan' }).click();
    await page.getByRole('link', { name: '' }).first().click();
    await page.getByRole('link', { name: ' Tambah' }).click();
    await page.getByRole('row', { name: '1 JOKO HARIANTO 081240002609' }).getByRole('checkbox').check();
    await page.getByRole('row', { name: '2 MUHAMMAD RIDWAN MAHFUDZ' }).getByRole('checkbox').check();
    await page.getByRole('button', { name: ' Simpan' }).click();
    await page.getByRole('link', { name: ' Kirim Pesan' }).click();
    await page.getByRole('link', { name: ' Kirim Pesan Grup' }).click();
    await page.getByRole('textbox', { name: 'Pilih Grup Kontak' }).click();
    await page.getByRole('treeitem', { name: 'test grup ( 2 Anggota )' }).click();
    await page.getByRole('textbox', { name: 'Subjek Pesan' }).click();
    await page.getByRole('textbox', { name: 'Subjek Pesan' }).fill('test kirim pesan');
    await page.getByRole('textbox', { name: 'Subjek Pesan' }).press('Tab');
    await page.getByRole('textbox', { name: 'Isi Pesan' }).fill('isi pesan disini');
    await page.getByRole('button', { name: ' Simpan' }).click();
    await expect(page.locator('#notifikasi')).toContainText('Kirim Pesan');
    await page.goto('daftar_kontak');
    await page.getByRole('link', { name: ' Grup' }).click();
    await page.getByRole('link', { name: '' }).click();
    await page.locator('#confirm-delete a').click();
  } catch { }
  });
});