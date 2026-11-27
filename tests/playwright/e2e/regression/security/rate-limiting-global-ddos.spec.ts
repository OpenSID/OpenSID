// Issue: #10631 — Terapkan rate limiting global untuk mencegah serangan DDoS
// https://github.com/OpenSID/OpenSID/issues/10631
import { test, expect } from '../../../utils/regressionTest';

test.describe('Terapkan Rate Limiting Global untuk Mencegah Serangan DDoS', () => {
  test('security: tambahkan rate limit harus mengembalikan status code 429 setelah melebihi batas permintaan per menit', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10631',
    },
  }, async ({ page }) => {
    const endpoint = '/siteman';
    let rateLimitedResponse: Awaited<ReturnType<typeof page.request.get>> | null = null;

    // Kirim permintaan sampai rate limit tercapai (maks 200 percobaan)
    for (let i = 1; i <= 200; i++) {
      const response = await page.request.get(endpoint);
      if (response.status() === 429) {
        rateLimitedResponse = response;
        break;
      }
      // Hanya izinkan 200 atau 302 (redirect) sebagai respon normal
      expect([200, 302]).toContain(response.status());
    }

    // Verifikasi rate limit pada akhirnya tercapai
    expect(rateLimitedResponse, 'Rate limiter harus mengembalikan 429 setelah melebihi batas').not.toBeNull();
    expect(rateLimitedResponse!.status()).toBe(429);

    // Verifikasi pesan error mengandung teks yang sesuai
    const errorMessage = await rateLimitedResponse!.text();
    expect(errorMessage.toLowerCase()).toContain('terlalu banyak permintaan');
  });
});
