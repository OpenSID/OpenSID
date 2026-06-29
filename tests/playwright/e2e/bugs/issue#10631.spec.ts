import { test, expect } from '@playwright/test';

test.describe('Terapkan Rate Limiting Global untuk Mencegah Serangan DDoS', () => {
  test('security: tambahkan rate limit harus mengembalikan status code 429 setelah melebihi 60 permintaan per menit', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10631',
    },
  }, async ({ page }) => {
    const endpoint = '/siteman';
    const rateLimit = 60;
    let tooManyRequestsStatus = false;
    let errorMessage = '';

    // 1. Send 60 requests (should all succeed with 200 status)
    for (let i = 1; i <= rateLimit; i++) {
      const response = await page.request.get(endpoint);
      expect(response.status()).toBe(200);
    }

    // 2. Send 61st request (should return 429 - Too Many Requests)
    const rateLimitedResponse = await page.request.get(endpoint);
    
    tooManyRequestsStatus = rateLimitedResponse.status() === 429;
    expect(tooManyRequestsStatus).toBe(true);

    // 3. Verify error message contains expected text
    errorMessage = await rateLimitedResponse.text();
    expect(errorMessage.toLowerCase()).toContain('too many requests');
  });
});
