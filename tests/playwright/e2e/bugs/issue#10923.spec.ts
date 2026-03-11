import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Nama penanda tangan tidak muncul pada cetak dan unduh data rekapitulasi penduduk #10923', () => {
  test('fix: perbaikan Nama penanda tangan tidak muncul pada cetak dan unduh data rekapitulasi penduduk', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10923',
    },
  }, async ({ page }) => {
    await page.goto('bumindes_penduduk_rekapitulasi');
    
    // Pastikan halaman berhasil dimuat
    await expect(page).not.toHaveURL(/error|404|500/);

    // Pastikan nama Kepala Desa (pamong_ttd) muncul
    const pamongTtd = page.locator('[data-testid="pamong-ttd"], .pamong-ttd, #pamong_ttd').first();
    await expect(pamongTtd).toBeVisible();
    await expect(pamongTtd).not.toBeEmpty();

    // Pastikan nama Sekretaris Desa (pamong_ketahui) muncul
    const pamongKetahui = page.locator('[data-testid="pamong-ketahui"], .pamong-ketahui, #pamong_ketahui').first();
    await expect(pamongKetahui).toBeVisible();
    await expect(pamongKetahui).not.toBeEmpty();
  });
});
