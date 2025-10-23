import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../../storage/auth/admin.json'),
});

test.describe('Ubah Kategori Tidak Tersedia #9393', () => {
  test('fix: perbaikan simpan jenis kategori dinamis dan statis', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/9393',
    },
  }, async ({ page }) => {
    await page.goto('web/statis');

    // dapatkan value dari gridcell, simpan untuk pengecekan di halaman berikutnya
    await page.getByRole('gridcell', { name: 'Sejarah Desa' }).click();

    // klik tombol ubah kategori
    await page.locator('.aksi > a:nth-child(3)').first().click();

    // pilih kategori statis keuangan
    await page.locator('select[name="kategori_statis"]').selectOption('keuangan');

    // simpan
    await page.locator('#ok').click();

    // redirect ke halaman statis keuangan
    // pastikan data yang ditampilkan adalah data yang diubah
    await expect(page.getByRole('link', { name: ' Tambah Keuangan' })).toBeVisible();
    await expect(page.getByRole('gridcell', { name: 'Sejarah Desa' })).toBeVisible();
  });
});
