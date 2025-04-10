import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../../storage/auth/admin.json'),
});

test.describe('Notifikasi tambah agar konsisten (#9419)', () => {
  test('fix: perbaikan notifikasi saat tambah data galeri', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/9419',
    },
  }, async ({ page }) => {
    // 1. Buka form tambah galeri (URL tokenized)
    await page.goto('gallery/form/eyJpdiI6IktmTFJsMlI2Zit5VVlhM2JzNkR6L1E9PSIsInZhbHVlIjoiVnZWQkgrOEN2by9GNW5BNCt6SmszUT09IiwibWFjIjoiYjkxNjljOTkwYzAzMGY4Njk5ZmYxMTRkZjU0NDE1YThjZjk4ZTIyMDg5YTRkOWZkMDFiNmIwYTMzYjEyMDkyMiIsInRhZyI6IiJ9');

    // 2. Isi form galeri
    await page.locator('input[name="nama"]').fill('foto 1');
    await page.locator('#jenis').selectOption('2');
    await page.getByRole('textbox', { name: 'Link/URL' }).fill(
      'https://images.pexels.com/photos/4016579/pexels-photo-4016579.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1'
    );

    // 3. Klik tombol Simpan
    await page.getByRole('button', { name: ' Simpan' }).click();

    // 4. Verifikasi notifikasi sukses
    await expect(page.locator('#notifikasi')).toContainText('Berhasil menambah data');
  });
});
