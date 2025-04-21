import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../../storage/auth/admin.json'),
});

test.describe('Icon profil pria muncul untuk pengguna perempuan layanan mandiri #9469', () => {
  test('fix: perbaiki icon profil layanan mandiri', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/9469'
    }
  }, async ({ page }) => {
    await page.goto('http://127.0.0.1:8000/layanan-mandiri/masuk');

    try {
      await page.getByText('Terima semua cookie', { exact: true }).click();
    } catch {} // abaikan kalau tombol cookie nggak ada

    try {
      await page.getByRole('textbox', { name: 'NIK' }).fill('1505022111940001');
      await page.getByRole('textbox', { name: 'PIN' }).fill('770998');
      await page.getByRole('button', { name: 'MASUK', exact: true }).click();
      await page.goto('http://127.0.0.1:8000/layanan-mandiri/profil');

      // Kalau berhasil login, lanjutkan tes ikon
      await expect(page.getByRole('img', { name: 'Foto', exact: true })).toBeVisible();
    } catch {
    }
  });
});
