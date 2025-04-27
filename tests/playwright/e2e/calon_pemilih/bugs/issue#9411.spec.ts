import { test, expect} from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../../storage/auth/admin.json'),
});

test.describe('Cetak/Unduh Data Calon Pemilih #9411', () => {
  test('fix: perbaiki cetak/unduh data calon pemilih', {
    annotation: {
        type: 'issue',
        description: 'https://github.com/OpenSID/OpenSID/issues/9411'
    }
  }, async ({ page }) => {
    await page.goto('dpt');
    await page.getByRole('combobox', { name: 'Pilih Jenis Kelamin' }).locator('span').nth(1).click();
    await page.getByRole('treeitem', { name: 'Laki-laki' }).click();
    await page.getByRole('link', { name: ' Pencarian Spesifik' }).click();
    await page.locator('div:nth-child(6) > .form-group > span > .selection > .select2-selection > .select2-selection__arrow').click();
    await page.getByRole('treeitem', { name: 'KAWIN', exact: true }).click();
    await page.getByText('Simpan', { exact: true }).click();
    await expect(page.locator('#tabeldata_info')).toContainText('Menampilkan 1 sampai 50');
    await page.getByRole('link', { name: ' Cetak' }).click();
    const page13Promise = page.waitForEvent('popup');
    await page.locator('#form-cetak').getByText('Cetak').click();
    const page13 = await page13Promise;
    await expect(page13.getByRole('heading')).toContainText('DAFTAR CALON PEMILIH UNTUK TANGGAL PEMILIHAN');
    const rows = await page13.locator('tbody tr');
    const rowCount = await rows.count();
    const lastRow = rows.nth(rowCount - 1);
    await expect(lastRow).toContainText('50');
  });
});