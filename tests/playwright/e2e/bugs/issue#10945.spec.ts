import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Tampilan text berjalan tidak berfungsi di browser versi terbaru #10945', () => {
  test('fix: perbaikan Tampilan text berjalan tidak berfungsi di browser versi terbaru', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10945',
    },
  }, async ({ page }) => {
    await page.goto('/');

    // Pastikan elemen marquee CSS ada dan terlihat
    const marqueeTrack = page.locator('.marquee-track, .marquee-track-natra').first();
    await expect(marqueeTrack).toBeVisible();

    // Pastikan tidak ada tag <marquee> lama yang masih digunakan
    const legacyMarquee = page.locator('marquee');
    await expect(legacyMarquee).toHaveCount(0);

    // Pastikan animasi CSS berjalan (animation-duration tidak kosong)
    const animationDuration = await marqueeTrack.evaluate(el => {
      return window.getComputedStyle(el).animationDuration;
    });
    expect(animationDuration).not.toBe('0s');
    expect(animationDuration).not.toBe('');

  });
});
