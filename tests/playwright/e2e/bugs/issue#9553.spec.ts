import { test, expect } from '@playwright/test';
import path from 'path';

// Gunakan sesi login admin yang telah disimpan
test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Data tidak sesuai pada Shortcut #9553', () => {
  test('fix: shortcut surat', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/9553',
    },
  }, async ({ page }) => {
    await page.goto('shortcut');
    await page.getByRole('link', { name: ' Tambah' }).click();
    await page.locator('input[name="judul"]').click();
    await page.locator('input[name="judul"]').fill('Surat');
    await page.locator('#select2-raw_query-container').click();
    await page.locator('input[type="search"]').fill('jumlah surat');
    await page.getByRole('treeitem', { name: 'Jumlah Surat', exact: true }).click();
    await page.locator('.input-group-addon').click();
    await page.locator('.colorpicker-saturation').click();
    await page.getByRole('textbox', { name: 'Tidak' }).click();
    await page.getByRole('treeitem', { name: 'Ya' }).click();
    await page.getByRole('button', { name: ' Simpan' }).click();
    await page.getByRole('link', { name: ' Layanan Surat ' }).click();
    await page.getByRole('link', { name: ' Pengaturan Surat' }).click();
    await expect(page.locator('#tabeldata_info')).toContainText('Menampilkan 1 sampai 25 dari 35 entri');
    await page.getByRole('link', { name: ' Beranda' }).first().click();
    await expect(page.locator('#maincontent')).toContainText('35 Surat');
    await page.getByRole('link', { name: ' Pengaturan ' }).click();
    await page.getByRole('link', { name: ' Shortcut' }).click();
    await page.getByRole('row', { name: ' 9    Surat  Aktif' }).getByRole('link').nth(2).click();
    await page.locator('#confirm-delete a').click();
  });
});
