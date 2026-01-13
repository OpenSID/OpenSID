import { test, expect } from '@playwright/test';

test.describe('Bug/error: Tombol terima cookie pada layanan mandiri tidak bisa diklik #10692', () => {
  test('fix: perbaiki hapus terima cookie sudah tidak digunakan #5780', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10692',
    },
  }, async ({ page }) => {
    await page.goto('layanan-mandiri/masuk');
    
    // Assert modal konfirmasi terima cookie tidak muncul karena sudah dihapus
    const konfirmasiCookieModal = page.locator('#konfirmasi-cookie');
    await expect(konfirmasiCookieModal).not.toBeVisible();
  });
});
