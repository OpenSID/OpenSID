import { test, expect } from '@playwright/test';

test.describe('Bug/error: Data pribadi pelapak tidak boleh terekspos di endpoint internal_api/lapak/pelapak #6117', () => {
  test('fix: endpoint harus return 404', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/premium/issues/6117',
    },
  }, async ({ request }) => {
    const response = await request.get('internal_api/lapak/pelapak');
    expect(response.status()).toBe(404);
  });
});
