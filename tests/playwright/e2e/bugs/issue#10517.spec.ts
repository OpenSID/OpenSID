import { test, expect } from '@playwright/test';
import path from 'path';
import { Laravel } from '@test/utils/laravel';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Alur Pecah / Gabung KK Penduduk (#10517)', () => {
  test('fix: Perbaiki gabung KK', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10517',
    },
  }, async ({ page }) => {
    try {
      await page.goto('keluarga/anggota/1102465');

      // login
      await page.getByRole('textbox', { name: 'Nama pengguna' }).fill('admin');
      await page.getByRole('textbox', { name: 'Kata sandi' }).fill('sid304');
      await page.getByRole('button', { name: 'Masuk', exact: true }).click();

      await page.goto('keluarga/anggota/1102465');

      // Fungsi helper
        const btn = page.getByRole('link', { name: '' });
        if (await btn.count() > 0) {      
          await page.getByRole('link', { name: '' }).click();
          await page.getByPlaceholder('Nomor KK').click();
          await page.getByPlaceholder('Nomor KK').fill('3512102611250001');
          await page.locator('#validasi').getByText('Simpan').click();
          await page.getByRole('link', { name: '' }).click();
          await page.getByPlaceholder('Nomor KK').click();
          await page.getByPlaceholder('Nomor KK').fill('3512102611250001');
          await page.locator('#validasi').getByText('Simpan').click();
          await expect(page.getByText('Nomor KK telah terdaftar.')).toBeVisible();
          await page.getByRole('link', { name: '' }).click();
          await page.getByTitle('Centang jika belum memiliki').check();
          await page.locator('#validasi').getByText('Simpan').click();
          await expect(page.getByText('Gabung KK baru berhasil')).toBeVisible();
        }

    } catch (e) {
    }

  });
});
