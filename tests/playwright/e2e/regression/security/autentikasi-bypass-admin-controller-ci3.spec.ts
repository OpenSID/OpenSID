// Regression guard: autentikasi CI3 Admin_Controller
//
// Admin_Controller.__construct() di donjo-app/core/Admin_Controller.php baris 71-75
// berisi auth check yang memproteksi 100+ CI3 admin controller.
// Pada commit edee169ea7 baris ini di-comment out sebagai "Temporarily comment out
// auth check for development", dan mengekspos seluruh route CI3 admin tanpa autentikasi
// — termasuk 154.514 data PII penduduk yang bisa diakses publik.
//
// CI3FallbackHandler hanya mengecek auth untuk Laravel controller; CI3 controller
// mengandalkan sepenuhnya guard di Admin_Controller. Jika guard dicomment lagi,
// tes-tes ini akan langsung gagal.
import { test, expect } from '@playwright/test';

test.describe('Autentikasi Admin_Controller — akses tanpa login harus ditolak', () => {
  // Tidak ada storageState = context browser bersih = tidak ada session aktif
  test.use({ storageState: { cookies: [], origins: [] } });

  const routes: { path: string; label: string }[] = [
    { path: 'penduduk', label: 'daftar penduduk (154k data PII)' },
    { path: 'keluarga', label: 'daftar keluarga' },
    { path: 'wilayah', label: 'wilayah administrasi' },
  ];

  for (const { path, label } of routes) {
    test(`GET /${path} (${label}) tanpa login → redirect ke /siteman`, async ({ page }) => {
      await page.goto(path);
      await expect(page).toHaveURL(/siteman/);
    });
  }
});
