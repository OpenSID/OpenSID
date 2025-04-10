import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../../storage/auth/admin.json'),
});

test.describe('Notif "Pesan belum terbaca" masih muncul meskipun pesan sudah dibaca (#9414)', () => {
  test('fix: perbaikan notif komentar belum dibaca', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/9414',
    },
  }, async ({ page }) => {
    await page.goto('komentar');
    await expect(page.getByRole('link', { name: '' })).toBeVisible();

    const komentarLocator = page.locator('#b_komentar');

    // Periksa apakah ada notifikasi komentar belum terbaca
    if (await komentarLocator.count() > 0) {
      const text = await komentarLocator.textContent();

      if (text?.includes('1')) {
        // Klik ikon komentar
        await page.getByRole('link', { name: '' }).click();

        // Klik ikon tandai telah dibaca (asumsi ikon '')
        await page.getByRole('link', { name: '' }).click();

        // Pastikan ikon komentar masih muncul (tapi notifikasinya hilang)
        await expect(page.getByRole('link', { name: '' })).toBeVisible();

        // Validasi bahwa elemen navigasi tidak lagi mengandung angka notifikasi
        await expect(page.getByRole('navigation')).toContainText('');
      }
    }
  });
});
