import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: gagal memuat anggota keluarga saat ubah status dasar pada data tertentu #11236', () => {
  test('fix: gagal memuat anggota keluarga saat ubah status dasar pada data tertentu', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11236',
    },
  }, async ({ page }) => {
    // 1. Buka halaman anggota keluarga
    await page.goto('keluarga/anggota/16637411');
    await page.waitForLoadState('networkidle');

    // 2. Klik tombol "Ubah Status Dasar" (icon fa-sign-out / bg-teal)
    const btnStatusDasar = page.locator('a.bg-teal').first();
    await expect(btnStatusDasar).toBeVisible();
    await btnStatusDasar.click();

    // 3. Pastikan modalBox terbuka
    const modalBox = page.locator('#modalBox');
    await expect(modalBox).toBeVisible();

    // 4. Pilih status dasar "Pindah" (value: 3)
    const selectStatusDasar = modalBox.locator('select#status_dasar');
    await expect(selectStatusDasar).toBeVisible();
    await selectStatusDasar.selectOption('3');

    // 5. Verifikasi pemuatan anggota keluarga sukses (tidak muncul pesan error)
    const errorAlert = modalBox.locator('text=Gagal memuat data anggota keluarga');
    await expect(errorAlert).not.toBeVisible();

    // 6. Pastikan tabel daftar anggota yang ikut pindah berhasil dimuat
    const infoText = modalBox.locator('text=Centang anggota keluarga yang akan ikut pindah bersama');
    await expect(infoText).toBeVisible();
  });
});
