// Issue: #10910 — Klik setujui izin — modal alert muncul pesan penghapusan dan tidak bisa klik setujui
// https://github.com/OpenSID/OpenSID/issues/10910
import { test, expect } from '../../../utils/regressionTest';


test.describe('Bug/error: klik setujui Izin Modal alert muncul Pesan Penghapusan dan tidak bisa klik setujui #10911', () => {
  test('fix: perbaiki tombol tolak dan setujui tidak berfungsi', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10911',
    },
  }, async ({ page }) => {
    await page.goto('kehadiran_pengajuan_izin');

    await expect(page.locator('tbody')).toContainText('Dinas Luar Kota');
  });
});