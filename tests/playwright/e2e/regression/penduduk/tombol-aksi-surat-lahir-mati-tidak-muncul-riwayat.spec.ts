// Issue: #10898 — Tombol aksi buat surat keterangan lahir mati tidak muncul di riwayat mutasi penduduk
// https://github.com/OpenSID/OpenSID/issues/10898
import { test, expect } from '../../../utils/regressionTest';


test.describe('Bug/error: tombol aksi buat surat keterangan lahir mati tidak muncul di riwayat mutasi penduduk #10898', () => {
  test('fix: perbaiki tombol aksi buat surat tidak muncul di riwayat mutasi penduduk', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10898',
    },
  }, async ({ page }) => {
    await page.goto('penduduk_log');
    await page.bringToFront();
    await page.waitForSelector('table#tabeldata tbody tr', { timeout: 10000 });
    await expect(page.locator('table#tabeldata tbody tr')).not.toHaveCount(0);
    // Verify action buttons are present in each row
    await expect(page.locator('table#tabeldata tbody tr:first-child a[data-toggle="modal"]').first()).toBeVisible();
    // Verify page renders correctly (not beranda)
    await expect(page.locator('body')).not.toContainText('Tentang OpenSID');
    // Verify page content section is visible
    await expect(page.locator('section.content-header, #maincontent').first()).toBeVisible();
  });
});
