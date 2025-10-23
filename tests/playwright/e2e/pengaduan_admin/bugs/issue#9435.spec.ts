import { test, expect } from '@playwright/test';
import path from 'path';
import { Laravel } from '../../../laravel';

// Gunakan sesi login admin yang telah disimpan
test.use({
  storageState: path.resolve(__dirname, '../../../storage/auth/admin.json'),
});

test.beforeEach(async ({ page }) => {
  await Laravel.query(`
    INSERT INTO pengaduan (id, config_id, id_pengaduan, nik, nama, email, telepon, judul, isi, status, foto, ip_address, created_at, updated_at) VALUES
      (1, 1, NULL, '0720110200700001', 'Anonim', 'anonim@mail.com', '08131111111', 'Contoh Judul Pengaduan', 'Isi contoh pengaduan', 2, 'kjj6R_contoh_judul_pengaduan_11_04_2025.webp', '127.0.0.1', '2025-04-11 07:21:27', '2025-04-11 07:22:17'),
      (2, 1, 1, NULL, 'RIYADI', NULL, NULL, NULL, 'Contoh balasan sedang proses', 2, NULL, '127.0.0.1', '2025-04-11 07:22:17', '2025-04-11 07:22:17');
  `, [], { unprepared: true });
});

test.describe('Bug/error: Link pada teks "Administrator" tidak mengarahkan ke halaman manapun #9435', () => {
  test('fix: perbaiki text detail tanggapan pengaduan', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/9435',
    },
  }, async ({ page }) => {
    await page.goto('/pengaduan_admin/detail/1');
    await expect(page.getByText('Contoh balasan sedang proses')).toBeVisible();

    const allPrimary = page.locator('.text-primary');
    const count = await allPrimary.count();

    for (let i = 0; i < count; i++) {
      const tag = await allPrimary.nth(i).evaluate(el => el.tagName);
      expect(tag).not.toBe('A');
    }
  });
});
