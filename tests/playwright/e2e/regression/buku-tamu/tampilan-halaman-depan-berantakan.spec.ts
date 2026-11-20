// Issue: #6419 — Tampilan halaman depan Buku Tamu Berantakan
// https://github.com/OpenSID/premium/issues/6419
import { test, expect } from '../../../utils/regressionTest';

test.describe('Bug: Tampilan halaman depan Buku Tamu berantakan #6419', () => {
  test('fix: aset CSS dan JS modul buku tamu berhasil dimuat (bukan 404)', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/premium/issues/6419',
    },
  }, async ({ page }) => {
    // Aset CSS utama harus tersedia agar layout tidak berantakan.
    // Sebelum perbaikan: Views/assets/ tidak ada → Asset controller kembalikan 404.
    const styleResponse = await page.request.get('module_asset/bukutamu?file=css/style.css');
    expect(styleResponse.status()).toBe(200);
    expect(styleResponse.headers()['content-type']).toContain('text/css');

    const bootstrapResponse = await page.request.get('module_asset/bukutamu?file=css/bootstrap.min.css');
    expect(bootstrapResponse.status()).toBe(200);

    // JS support.js sebelumnya di-load via asset('buku/js/support.js') yang tidak ada di public/.
    // Setelah perbaikan harus dimuat via module_asset.
    const supportJsResponse = await page.request.get('module_asset/bukutamu?file=js/support.js');
    expect(supportJsResponse.status()).toBe(200);
  });
});
