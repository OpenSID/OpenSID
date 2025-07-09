import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Pengaturan surat dinas #9787', () => {
  test('fix: perbaiki format tanggal surat pengaturan surat dinas', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/9787',
    },
  }, async ({ page }) => {
    await page.goto('surat_dinas/pengaturan');
    await page.getByRole('link', { name: 'Lainnya' }).click();
    await expect(page.getByRole('textbox', { name: 'd F Y' })).toHaveValue('d F Y');
    await page.getByRole('button', { name: ' Simpan' }).click();
    await expect(page.locator('#notifikasi')).toContainText('Berhasil Ubah Data');
    await page.getByRole('link', { name: ' Pengaturan', exact: true }).click();
    await page.getByRole('link', { name: 'Lainnya' }).click();
    await expect(page.getByRole('textbox', { name: 'd F Y' })).toHaveValue('d F Y');
  });
});