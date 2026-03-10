import { test, expect } from '@playwright/test';
import path from 'path';
import { Laravel } from '../../utils/laravel';

test.describe('Bug/error: Muncul notif layanan hosting berakhir padahal sudah pindah layanan #10900', () => {
  test('fix: perbaikan Muncul notif layanan hosting berakhir padahal sudah pindah layanan', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10900'
    }
  }, async ({ page }) => {
      // 1. Login dan navigasi ke dashboard
        await page.goto('/beranda');
        await page.waitForLoadState('networkidle');

        // 2. Pastikan notifikasi hosting expired TIDAK muncul
        //    karena desa sudah pindah ke SiapPakai yang mencakup hosting
        const notifHostingExpired = page.locator('[data-status-key="hosting_expired"]');
        await expect(notifHostingExpired).not.toBeVisible();

        // 3. Pastikan teks pesan hosting expired tidak muncul di halaman
        await expect(page.locator('body')).not.toContainText('Layanan Hosting');
        await expect(page.locator('body')).not.toContainText('telah berakhir');

        // 4. Pastikan invoice lama hosting tidak ikut muncul di notifikasi
        await expect(page.locator('body')).not.toContainText('INV/20240109');
  });
});
