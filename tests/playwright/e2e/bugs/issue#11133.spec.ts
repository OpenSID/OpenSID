import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/Error: Gagal menmpilkan data pada halaman rekapitulasi penduduk #11133', () => {
  test('fix: perbaikan menampilkan data pada halaman rekapitulasi penduduk', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11133',
    },
  }, async ({ page }) => {
    await page.goto('bumindes_penduduk_rekapitulasi');
    
    // Pastikan halaman berhasil dimuat
    await expect(page).not.toHaveURL(/error|404|500/);
  });
});
