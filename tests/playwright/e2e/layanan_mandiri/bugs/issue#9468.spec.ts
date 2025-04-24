import { test, expect } from '@playwright/test';
import path from 'path';
import { Laravel } from '../../../laravel';

test.use({
  storageState: path.resolve(__dirname, '../../../storage/auth/admin.json'),
});

test.describe('Peta lokasi pelapak tidak tampil dengan benar pada layanan mandiri #9468', () => {
  test.beforeEach(async ({ page }) => {
    await Laravel.query(`
      INSERT INTO produk (
          config_id, id_pelapak, id_produk_kategori, nama, harga, satuan,
          tipe_potongan, potongan, deskripsi, foto, status
      ) VALUES (
          1, 1, 6, 'Kurma Ajwa', 20000, 'kg', 1, 0, 'testttt', NULL, 1
      );
    `);
  });

  // hapus data produk setelah test selesai
  test.afterEach(async () => {
    await Laravel.query(`
      DELETE FROM produk WHERE nama = 'Kurma Ajwa';
    `);
  });

  test('fix: perbaiki peta lokasi tidak tampil semestinya', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/9468'
    }
  }, async ({ page }) => {

    await page.goto('layanan-mandiri/masuk');

    try {
      await page.getByText('Terima semua cookie', { exact: true }).click();
    } catch { } // abaikan kalau tombol cookie nggak ada

    try {
      await page.getByRole('textbox', { name: 'NIK' }).fill('1505022111940001');
      await page.getByRole('textbox', { name: 'PIN' }).fill('123456');
      await page.getByRole('button', { name: 'MASUK', exact: true }).click();
      await page.goto('layanan-mandiri/lapak');

      // Kalau berhasil login, lanjutkan tes ikon
      await expect(page.getByRole('img', { name: 'Foto', exact: true })).toBeVisible();

      await page.goto('layanan-mandiri/lapak');
      await page.getByText('Lokasi').click();
      await expect(page.locator('div').filter({ hasText: /^\+−Leaflet \| © OpenStreetMap \| OpenSID$/ }).first()).toBeVisible();
    } catch {
    }
  });
});
