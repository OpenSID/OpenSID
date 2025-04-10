import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Modul Beranda', () => {
  test('Menampilkan halaman beranda', async ({ page }) => {
    // 1. Akses halaman beranda
    await page.goto('beranda');

    // 2. Validasi link navigasi aktif ke 'Beranda' terlihat
    await expect(page.getByRole('link', { name: 'Beranda' }).first()).toBeVisible();

    // 3. Validasi judul/heading 'Tentang OpenSID' tampil
    await expect(page.getByRole('heading', { name: 'Tentang OpenSID' })).toBeVisible();

    // 4. Validasi isi accordion mengandung teks tentang OpenSID
    await expect(page.locator('#accordion')).toContainText('Aplikasi OpenSID');

    // 5. Validasi isi dari panel collapse mengandung deskripsi aplikasi
    await expect(page.locator('#collapse1')).toContainText('OpenSID adalah aplikasi Sistem Informasi Desa');
  });
});
