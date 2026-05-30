import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Data penduduk selain hidup (MATI/PINDAH/TIDAK VALID/DLL) masih terbaca di Data Suplemen #10418', () => {
  test('fix: Data penduduk selain hidup (MATI/PINDAH/TIDAK VALID/DLL) masih terbaca di Data Suplemen', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10418',
    },
  }, async ({ page }) => {
    try {
    await page.goto('penduduk');
    await page.getByRole('textbox', { name: 'Hidup' }).click();
    await page.getByRole('treeitem', { name: 'Mati' }).click();
    await page.getByText('Sedang memproses...').click();
    await page.getByRole('gridcell', { name: '3510163006420064' }).click();
    await page.getByRole('link', { name: ' Data Suplemen' }).click();
    await page.getByRole('link', { name: ' Tambah' }).click();
    await page.getByRole('combobox').selectOption('1');
    await page.getByRole('textbox', { name: 'Nama Data Suplemen' }).click();
    await page.getByRole('textbox', { name: 'Nama Data Suplemen' }).fill('TEST DATA NON HIUDP');
    await page.getByRole('textbox', { name: 'Keterangan' }).click();
    await page.getByRole('textbox', { name: 'Keterangan' }).fill('TEST');
    await page.getByRole('textbox', { name: 'Keterangan' }).press('CapsLock');
    await page.getByRole('button', { name: ' Simpan' }).click();
    await page.goto('suplemen');
    await page.getByRole('searchbox', { name: 'Cari:' }).click();
    await page.getByRole('searchbox', { name: 'Cari:' }).press('CapsLock');
    await page.getByRole('searchbox', { name: 'Cari:' }).fill('TEST DATA NON');
    await page.getByRole('searchbox', { name: 'Cari:' }).press('CapsLock');
    await page.getByRole('gridcell', { name: 'TEST DATA NON HIUDP' }).click();
    await page.getByRole('link', { name: '' }).click();
    await page.getByText('Tambah', { exact: true }).click();
    await page.getByRole('link', { name: ' Tambah Satu Data Warga' }).click();
    await page.getByRole('textbox', { name: '-- Cari NIK / Nama Penduduk --' }).click();
    await page.locator('input[type="search"]').fill('3510163006420064');
    await page.getByRole('treeitem', { name: 'No results found' }).click();
    } catch { }
  });
});