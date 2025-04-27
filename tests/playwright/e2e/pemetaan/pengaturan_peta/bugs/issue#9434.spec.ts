import { test, expect } from '@playwright/test';
import path from 'path';
import { Laravel } from '../../../../laravel';

test.use({
  storageState: path.resolve(__dirname, '../../../../storage/auth/admin.json'),
});

test.beforeEach(async ({ page }) => {
  await Laravel.query(`
    INSERT INTO line (config_id, nama, simbol, color, tipe, tebal, jenis, parrent, enabled) VALUES (1, 'Jalan Raya', NULL, '#000000', 0, 3, 'solid', 1, 1);
  `);
});

test.describe('Bug/error: Pesan Ubah Status Tipe Garis #9434', () => {
  test('fix: perbaikan notifikasi saat aktif atau nonaktifkan pengaturan tipe garis', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/9434',
    },
  }, async ({ page }) => {
    await page.goto('line');
    await page.locator('a.btn:has(i.fa.fa-unlock), a.btn:has(i.fa.fa-lock)').first().click();
    await expect(page.locator('#notifikasi')).toContainText('Berhasil mengubah status data');
  });
});
