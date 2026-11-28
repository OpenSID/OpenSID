// Issue: #9968 — Belum bisa auto replay di anjungan
// https://github.com/OpenSID/OpenSID/issues/9968
import { test, expect } from '../../../utils/regressionTest';


test.describe('Bug/error: belum bisa auto replay #9968', () => {
  test('fix: Perbaikan validasi form pengaturan dan penambahan autoplay', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/9968',
    },
  }, async ({ page }) => {
    // Setup: daftarkan perangkat anjungan baru untuk mendapatkan cookie UUID
    await page.goto('anjungan/form');
    // Never use networkidle on admin pages — background polling blocks forever.
    // Wait for the UUID input to be populated by the server instead.
    await page.waitForSelector('#anjungan_id', { timeout: 15000 });

    const uuid = await page.inputValue('#anjungan_id');
    const ipAddress = `192.168.99.${Math.floor(Math.random() * 253) + 1}`;
    const macAddress = `00:AA:BB:CC:DD:${Math.floor(Math.random() * 99).toString().padStart(2, '0')}`;

    await page.locator('#ip_address').fill(ipAddress);
    await page.locator('#mac_address').fill(macAddress);
    await page.locator('#tambahDaftarAnjungan').click();
    await page.waitForURL('**/anjungan');

    // Set cookie anjungan_uuid agar bisa mengakses halaman anjungan-mandiri
    const domain = new URL(page.url()).hostname;
    await page.context().addCookies([{
      name: 'anjungan_uuid',
      value: uuid,
      domain: domain,
      path: '/'
    }]);

    // Atur URL YouTube di pengaturan anjungan
    await page.goto('anjungan_pengaturan');

    // Pastikan profil diset ke Youtube (pilih "Youtube" di dropdown tampilan_profil)
    await page.locator('select[name="tampilan_profil"]').selectOption('3');
    await page.locator('#youtubeInput').waitFor({ state: 'visible' });
    await page.locator('#youtubeInput').fill('https://www.youtube.com/embed/PuxiuH-YUF4');
    await page.getByRole('button', { name: /Simpan/ }).click();
    // Wait for the save to complete — notification or redirect back to the same page.
    await expect(page.locator('#notifikasi')).toBeVisible({ timeout: 15000 });

    // Buka halaman anjungan-mandiri
    await page.goto('anjungan-mandiri');

    const iframe = page.locator('iframe.video-view');
    await expect(iframe).toBeVisible({ timeout: 10000 });

    const src = await iframe.getAttribute('src');
    expect(src).toContain('youtube.com/embed/');
    expect(src).toContain('autoplay=1');

    const match = src?.match(/embed\/([a-zA-Z0-9_-]{11})/);
    expect(match).not.toBeNull();

    // Cleanup: hapus anjungan yang dibuat
    await page.goto('anjungan');
    // Wait for the table to render before looking for the row.
    await page.waitForSelector('table tbody tr', { timeout: 15000 });
    const row = page.locator('tr', { hasText: macAddress });
    if (await row.isVisible()) {
      await row.locator('input[name="id_cb[]"]').check();
      await page.locator('a.hapus-terpilih').click();
      await expect(page.locator('#confirm-input')).toBeFocused({ timeout: 5000 });
      await page.locator('#confirm-input').pressSequentially('HAPUS');
      await expect(page.locator('#ok-delete')).toBeEnabled({ timeout: 3000 });
      await page.locator('#ok-delete').click();
    }
  });
});
