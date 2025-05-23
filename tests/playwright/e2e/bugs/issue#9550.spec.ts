import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Tidak Ada Pesan Notifikasi Saat Impor File GPX #9550', () => {
  test('fix: tambah notifikasi berhasil impor gpx', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/9550'
    }
  }, async ({ page }) => {
    await page.goto('lapak_admin/pelapak_maps/1');

    await page.getByRole('link', { name: 'file icon' }).click();
    await page.getByRole('link', { name: 'file icon' }).setInputFiles('OpenSID.gpx');
    await expect(page.getByText('Berhasil memuat GPX')).toBeVisible();
  });
});