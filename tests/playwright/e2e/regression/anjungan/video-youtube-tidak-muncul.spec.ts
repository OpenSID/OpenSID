// Issue: #10871 — Video YouTube anjungan tidak muncul
// https://github.com/OpenSID/OpenSID/issues/10871
import { test, expect } from '../../../utils/regressionTest';
import { Laravel } from '../../../utils/laravel';


test.describe('Bug/error: VIDEO YOUTUBE ANJUNGAN TIDAK MUNCUL #10871', () => {
  test('fix: perbaikan vidio anjungan yotube tidak muncul', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10871'
    }
  }, async ({ page }) => {
    // Setup: daftarkan perangkat anjungan baru untuk mendapatkan cookie UUID
    await page.goto('anjungan/form');
    await page.waitForLoadState('networkidle');

    const uuid = await page.inputValue('#anjungan_id');
    const ipAddress = `192.168.88.${Math.floor(Math.random() * 253) + 1}`;
    const macAddress = `00:FF:EE:DD:CC:${Math.floor(Math.random() * 99).toString().padStart(2, '0')}`;

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

    // Pastikan profil diset ke Youtube
    await page.locator('select[name="tampilan_profil"]').selectOption('3');
    await page.locator('#youtubeInput').waitFor({ state: 'visible' });
    await page.locator('#youtubeInput').fill('https://www.youtube.com/embed/PuxiuH-YUF4');
    await page.getByRole('button', { name: /Simpan/ }).click();
    await page.waitForLoadState('networkidle');

    // Buka halaman anjungan-mandiri
    await page.goto('anjungan-mandiri');
    await page.waitForLoadState('networkidle');

    const iframe = page.locator('iframe.video-view');
    await expect(iframe).toBeVisible({ timeout: 10000 });

    const src = await iframe.getAttribute('src');
    expect(src).toContain('youtube.com/embed/');
    expect(src).toContain('autoplay=1');

    const match = src?.match(/embed\/([a-zA-Z0-9_-]{11})/);
    expect(match).not.toBeNull();

    // Cleanup: hapus anjungan yang dibuat
    await page.goto('anjungan');
    await page.waitForLoadState('networkidle');
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
