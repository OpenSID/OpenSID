import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Informasi Validasi Gagal Pada Tab #9795', () => {
  test('fix: perbaiki Informasi Validasi Gagal Pada Tab', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/9795',
    },
  }, async ({ page }) => {
    await page.goto('surat_dinas/pengaturan');
    await page.getByRole('link', { name: 'Lainnya' }).click();
    await page.getByRole('textbox', { name: 'd F Y' }).fill('');
    await page.getByRole('link', { name: 'Header' }).click();
    await page.getByRole('button', { name: ' Simpan' }).click();
    await expect(page.getByText('Kolom ini diperlukan.')).toBeVisible();
    await page.getByRole('textbox', { name: 'd F Y' }).fill('d F Y');
    await page.getByRole('button', { name: ' Simpan' }).click();
    await expect(page.locator('#notifikasi')).toContainText('Berhasil Ubah Data');
  });
});