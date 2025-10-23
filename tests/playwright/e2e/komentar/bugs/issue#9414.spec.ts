import { test, expect } from '@playwright/test';
import { Laravel } from '../../../laravel';
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
    await Laravel.select("DELETE FROM `komentar` WHERE `id` = 1");
    await Laravel.select("INSERT INTO `komentar` (`id`, `config_id`, `id_artikel`, `owner`, `email`, `subjek`, `komentar`, `tgl_upload`, `status`, `tipe`, `no_hp`, `updated_at`, `is_archived`, `permohonan`, `jenis`, `parent_id`) VALUES (1, 1, 110, 'pengunjung', 'pengunjung@gmail.com', NULL, 'test', '2025-04-08 08:20:23', 2, NULL, '082111111111', '2025-04-08 08:20:23', 0, NULL, NULL, NULL)");
    await Laravel.artisan('cache:clear');

    await page.waitForTimeout(5000);
    await page.goto('komentar');
    await expect(page.getByRole('link', { name: '' })).toBeVisible();

    const komentarLocator = page.locator('#b_komentar');

    if (await komentarLocator.count() > 0) {
      const text = await komentarLocator.textContent();

      if (text?.includes('1')) {
        await page.getByRole('link', { name: '' }).click();
        await page.getByRole('link', { name: '' }).click();

        await expect(page.getByRole('link', { name: '' })).toBeVisible();
        await expect(page.getByRole('navigation')).toContainText('');
      }
    }
  });
});
