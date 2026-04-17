import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: tombol kembalikan penduduk tidak tampil di riwayat mutasi penduduk #11038', () => {
  test('fix: tombol kembalikan penduduk tidak tampil di riwayat mutasi penduduk#11038', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11038',
    },
  }, async ({ page }) => {
    await page.goto('/penduduk_log');
    await expect(page.getByText('Panduan', { exact: true })).toBeVisible();
    await page.getByText('Panduan', { exact: true }).click();
    await expect(page.getByText('Panduan Riwayat Mutasi')).toBeVisible();
    await expect(page.getByLabel('Panduan Riwayat Mutasi')).toContainText('Tombol Kembalikan Penduduk (serta checkbox tindakan masal) hanya akan muncul pada histori jika memenuhi ketiga syarat berikut secara bersamaan: Status dasar penduduk saat ini adalah Pindah atau Pergi. Histori ini merupakan histori kepergian terakhir untuk individu tersebut. Telah berganti bulan sejak tanggal lapor histori kepergian tersebut dilakukan.');
  });
});