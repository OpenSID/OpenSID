import { test, expect } from '@playwright/test';
import path from 'path';
import { Laravel } from '../../utils/laravel';

test.describe('Bug/error: Malfungsi Penutupan Overlay pada Navigasi Sidebar pada Tampilan Mobile #10889', () => {
  test('fix: perbaikan Malfungsi Penutupan Overlay pada Navigasi Sidebar pada Tampilan Mobile', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10889'
    }
  }, async ({ page }) => {
      // 1. Set viewport ke ukuran mobile untuk menguji perilaku toggle
    await page.setViewportSize({ width: 767, height: 800 });

    // 2. Buka halaman admin mana pun, contoh: penduduk
    await page.goto('penduduk');
    await page.waitForLoadState('networkidle');

    // 3. Buka sidebar kanan (control-sidebar) dengan mengklik tombolnya
    const rightSidebarToggle = page.locator('a[data-toggle="control-sidebar"]');
    await expect(rightSidebarToggle).toBeVisible();
    await rightSidebarToggle.click();

    // 4. Verifikasi bahwa sidebar kanan telah terbuka
    // Body dan elemen .control-sidebar akan memiliki class 'control-sidebar-open'
    await expect(page.locator('body')).toHaveClass(/control-sidebar-open/);
    await expect(page.locator('.control-sidebar')).toHaveClass(/control-sidebar-open/);
    await page.waitForTimeout(500); // Tunggu animasi transisi selesai

    // 5. Klik tombol toggle sidebar kiri (push-menu)
    const leftSidebarToggle = page.locator('a.sidebar-toggle[data-toggle="push-menu"]');
    await expect(leftSidebarToggle).toBeVisible();
    await leftSidebarToggle.click();

    // 6. Verifikasi bahwa sidebar kanan sekarang tertutup
    // Class 'control-sidebar-open' seharusnya sudah dihapus dari body dan elemen .control-sidebar
    await page.waitForTimeout(500); // Tunggu animasi transisi selesai
    await expect(page.locator('body')).not.toHaveClass(/control-sidebar-open/);
    await expect(page.locator('.control-sidebar')).not.toHaveClass(/control-sidebar-open/);
  });
});
