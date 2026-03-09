import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: klik setujui Izin Modal alert muncul Pesan Penghapusan dan tidak bisa klik setujui #10911', () => {
  test('fix: perbaiki tombol tolak dan setujui tidak berfungsi', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10911',
    },
  }, async ({ page }) => {
    await page.goto('kehadiran_pengajuan_izin');

    await page.getByTitle('Setujui Pengajuan').first().click();
    await expect(page.locator('#confirm-status')).toContainText('Apakah Anda yakin ingin menyetujui pengajuan izin ini?');
    await page.getByText('Tutup').click();
    await page.getByTitle('Tolak Pengajuan').first().click();
    await expect(page.locator('#confirm-status')).toContainText('Apakah Anda yakin ingin menolak pengajuan izin ini?');
  });
});
