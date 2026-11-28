// Issue: #10650 — Informasi pada tabel log aktivitas kolom pengguna tidak sesuai
// https://github.com/OpenSID/OpenSID/issues/10650
import { test, expect } from '../../../utils/regressionTest';


test.describe('Bug/error: Informasi pada Tabel Log Aktifitas kolom Pengguna tidak sesuai. #10650', () => {
  test('fix: Informasi pada Tabel Log Aktifitas kolom Pengguna tidak sesuai.', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10650',
    },
  }, async ({ page }) => {
    // 1. Pergi ke halaman log aktivitas
    await page.goto('/info_sistem#log_aktifitas');
    
    // 2. Tunggu hingga baris pertama pada tabel log muncul (menandakan data telah dimuat)
    await expect(page.locator('#tabel-logaktifitas tbody tr').first()).toBeVisible();

    // 3. Ambil sel "Pengguna" dari baris pertama (log terbaru)
    //    Kolom "Pengguna" adalah kolom ke-7 (indeks ke-6)
    const userCell = page.locator('#tabel-logaktifitas tbody tr').first().locator('td').nth(6);

    // 4. Verifikasi kolom Pengguna menampilkan nama + grup pengguna yang benar
    //    (akun admin seed dirender sebagai "Administrator (Administrator)").
    await expect(userCell).toHaveText('Administrator (Administrator)');
    
  });
});
