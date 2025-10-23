import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../../../storage/auth/admin.json'),
});

test.describe('Bug/error: Fungsi tombol batal pada ubah data Pengaturan Surat #9448', () => {
  test('fix: perbaiki tombol batal pada form ubah data pengaturan surat', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/9448',
    },
  }, async ({ page }) => {
    await page.goto('surat_master');

    await page.locator('a.btn.btn-warning[title="Ubah Data"]').first().click();
    await expect(page.getByRole('heading', { name: 'Surat Keterangan Kurang Mampu' })).toBeVisible();
    await expect(page.getByText('Syarat Surat')).toBeVisible();
    await expect(page.getByRole('gridcell', { name: 'NAMA DOKUMEN: aktifkan untuk' })).toBeVisible();
    await page.getByRole('button', { name: 'Batal' }).click();
    await expect(page.getByText('Syarat Surat')).toBeVisible();
    await expect(page.getByRole('gridcell', { name: 'NAMA DOKUMEN: aktifkan untuk' })).toBeVisible();
  });
});
