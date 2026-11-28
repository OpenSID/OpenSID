// Issue: #10716 — Fungsi ambil foto dari kamera di menu data anggota kelompok tidak berfungsi
// https://github.com/OpenSID/OpenSID/issues/10716
import { test, expect } from '../../../utils/regressionTest';


test.describe('Bug/error: Fungsi Ambil Foto dari Kamera di Menu Data Anggota Kelompok tidak berfungsi #10716', () => {
  test('fitur kamera harus berfungsi di halaman ubah biodata penduduk', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10716',
    },
  }, async ({ page, context }) => {
    test.setTimeout(60000);

    // Berikan izin kamera ke browser context
    await context.grantPermissions(['camera']);

    // Navigasi ke halaman admin kelompok (halaman asal bug ini)
    const response = await page.goto('/kelompok');
    await page.waitForLoadState('domcontentloaded');

    // Bug #10716: server mengirim Permissions-Policy: camera=() di halaman admin,
    // memblokir akses kamera. Setelah fix, header ini tidak boleh ada di admin.
    const headers = response?.headers() ?? {};
    const permissionsPolicy = headers['permissions-policy'] ?? '';

    // Pastikan halaman admin tidak mengirim Permissions-Policy yang memblokir kamera
    expect(permissionsPolicy).not.toContain('camera=()');

    // Verifikasi MediaDevices API tersedia di browser (diperlukan untuk fungsi kamera)
    const mediaDevicesAvailable = await page.evaluate(() => {
      return typeof navigator.mediaDevices !== 'undefined' &&
             typeof navigator.mediaDevices.getUserMedia === 'function';
    });
    expect(mediaDevicesAvailable).toBe(true);
  });
});
