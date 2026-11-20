// Issue: #10674 — Akses halaman buku tamu error 500 di rilis 2601.0.0-Premium
// https://github.com/OpenSID/OpenSID/issues/10674
import { test, expect } from '../../../utils/regressionTest';

test.describe('Bug/error: Akses Halaman Buku Tamu error 500 di Rilis 2601.0.0-Premium #10674', () => {
  test('fix: perbaikan akses halaman buku tamu', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10674',
    },
  }, async ({ page }) => {
    const response = await page.request.get('buku-tamu');

    // assert halaman dimuat tanpa error response code 500
    await expect(response.status()).not.toBe(500);
  });
});
