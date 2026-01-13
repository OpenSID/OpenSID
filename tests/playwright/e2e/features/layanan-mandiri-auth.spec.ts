import { test, expect } from '@playwright/test';

/**
 * Test untuk Issue #10702: Bug login layanan mandiri error "Anjungan tidak ditemukan"
 * 
 * BUG:
 * Saat masuk ke layanan-mandiri dengan NIK dan PIN sembarang, 
 * muncul error "Gagal Anjungan Tidak ditemukan"
 * 
 * EXPECTED:
 * Tidak boleh muncul error "Anjungan tidak ditemukan" 
 * karena tidak semua desa memiliki anjungan/lisensi
 */

test('should NOT show "Anjungan tidak ditemukan" error when login with random NIK and PIN', async ({ page }) => {
  // 1. Masuk ke domain/layanan-mandiri/masuk
  await page.goto('/layanan-mandiri/masuk');
  await page.waitForLoadState('networkidle');
  
  // 2. Masukkan NIK sembarang (16 digit)
  await page.getByPlaceholder(/NIK/i).fill('1234567890123456');
  
  // 3. Masukkan PIN sembarang
  await page.getByPlaceholder(/Password|Kata Sandi|PIN/i).fill('sembarang123');
  
  // 4. Klik masuk
  await page.getByRole('button', { name: /Masuk|Login/i }).click();
  
  // Tunggu response
  await page.waitForLoadState('networkidle');
  await page.waitForTimeout(1000);
  
  // 5. Pastikan TIDAK ada error "Anjungan tidak ditemukan"
  const anjunganError = page.getByText(/Anjungan tidak ditemukan/i);
  await expect(anjunganError).not.toBeVisible();
  
  // Pastikan juga tidak ada error "Anjungan belum diaktifkan"
  const anjunganInactiveError = page.getByText(/Anjungan belum diaktifkan/i);
  await expect(anjunganInactiveError).not.toBeVisible();
});
