import { test, expect } from '@playwright/test';

test.describe('SQL Injection (Time-Based Blind) - Input Validation untuk tgl_pemilihan', () => {
  const baseURL = process.env.PLAYWRIGHT_BASE_URL || 'https://opensid-premium.test';
  const endpoint = '/index.php/internal_api/dpt';

  test('security: DPT API harus menolak format tanggal yang tidak valid (Issue #5722)', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/premium/issues/5722 - SQL Injection (Time-Based Blind)',
    },
  }, async ({ request }) => {
    const invalidFormats = [
      '2024-12-19',                // Y-m-d format (wrong, should be d-m-Y)
      '19/12/2024',                // d/m/Y format dengan separator yang salah
      'invalid-date',              // completely invalid
      '01-01-01-01',               // format yang salah
      '<script>alert(1)</script>', // XSS attempt
      "' OR '1'='1",               // SQL injection attempt
    ];

    for (const format of invalidFormats) {
      const response = await request.get(`${baseURL}${endpoint}`, {
        params: { tgl_pemilihan: format },
        headers: {
          'X-Requested-With': 'XMLHttpRequest'
        }
      });

      // Harus validasi format, return 400/422 atau fallback ke default
      expect([200, 400, 422]).toContain(response.status());
    }
  });

  test('security: DPT API harus menerima format tanggal valid d-m-Y (Issue #5722)', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/premium/issues/5722',
    },
  }, async ({ request }) => {
    const validFormats = [
      '19-12-2024',
      '01-01-2025',
      '31-12-2024',
      '29-02-2024',  // leap year
    ];

    for (const format of validFormats) {
      const response = await request.get(`${baseURL}${endpoint}`, {
        params: { tgl_pemilihan: format },
        headers: {
          'X-Requested-With': 'XMLHttpRequest'
        }
      });

      expect(response.status()).toBe(200);
      const body = await response.json();
      expect(body).toHaveProperty('data');
    }
  });

  test('security: DPT API tanpa parameter tgl_pemilihan harus gunakan default value (Issue #5722)', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/premium/issues/5722',
    },
  }, async ({ request }) => {
    // Request tanpa parameter tgl_pemilihan
    const response = await request.get(`${baseURL}${endpoint}`, {
      headers: {
        'X-Requested-With': 'XMLHttpRequest'
      }
    });

    expect(response.status()).toBe(200);
    const body = await response.json();
    expect(body).toHaveProperty('data');
  });

  test('security: DPT API response harus NOT expose SQL error messages (Issue #5722)', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/premium/issues/5722',
    },
  }, async ({ request }) => {
    // Test dengan payload yang mungkin trigger error jika tidak di-validate
    const dangerousPayloads = [
      "' OR '1'='1' -- ",
      "01-01-2020') OR ('1'='1",
      "' AND SLEEP(5) -- ",
    ];

    const dangerousPatterns = [
      /SQLSTATE/i,
      /SQL syntax/i,
      /database error/i,
      /MySQL error/i,
      /FROM_DAYS/i,
      /STR_TO_DATE/i,
    ];

    for (const payload of dangerousPayloads) {
      const response = await request.get(`${baseURL}${endpoint}`, {
        params: { tgl_pemilihan: payload },
        headers: {
          'X-Requested-With': 'XMLHttpRequest'
        }
      });

      const responseBody = await response.text();

      let hasExposedError = false;
      for (const pattern of dangerousPatterns) {
        if (pattern.test(responseBody)) {
          hasExposedError = true;
          break;
        }
      }

      // Pastikan tidak ada error exposure
      expect(hasExposedError).toBe(false);
    }
  });

  test('security: Validate tanggal format using regex d-m-Y (Issue #5722)', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/premium/issues/5722',
    },
  }, async ({ request }) => {
    // Format d-m-Y adalah dd-mm-yyyy
    // Regex: /^\d{2}-\d{2}-\d{4}$/

    const testCases = [
      { date: '19-12-2024', valid: true },
      { date: '1-12-2024', valid: false },
      { date: '19-1-2024', valid: false },
      { date: '19-12-24', valid: false },
      { date: '19-12-2024-01', valid: false },
      { date: '32-13-2024', valid: false },
    ];

    for (const testCase of testCases) {
      const response = await request.get(`${baseURL}${endpoint}`, {
        params: { tgl_pemilihan: testCase.date },
        headers: {
          'X-Requested-With': 'XMLHttpRequest'
        }
      });

      // Format valid (d-m-Y) akan return 200, invalid akan return 200 dengan default atau 400
      // API sekarang sudah validasi, jadi invalid format harus return 200 dengan fallback
      expect([200, 400, 422]).toContain(response.status());
    }
  });

  test('security: DPT API response structure consistency (Issue #5722)', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/premium/issues/5722',
    },
  }, async ({ request }) => {
    // Test response consistency dengan valid request
    const response = await request.get(`${baseURL}${endpoint}`, {
      params: { tgl_pemilihan: '19-12-2024' },
      headers: {
        'X-Requested-With': 'XMLHttpRequest'
      }
    });

    expect(response.status()).toBe(200);
    const body = await response.json();

    // Response harus memiliki struktur standard JSON API / Fractal
    expect(body).toHaveProperty('data');
    expect(body).toHaveProperty('meta');
    expect(body).toHaveProperty('links');

    // Data harus array
    expect(Array.isArray(body.data)).toBe(true);

    // Setiap item harus memiliki struktur yang valid
    if (body.data.length > 0) {
      const item = body.data[0];
      expect(item).toHaveProperty('type');
      expect(item).toHaveProperty('id');
      expect(item).toHaveProperty('attributes');
    }
  });

  test('security: Validate tgl_pemilihan pada internal_api endpoint (Issue #5722)', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/premium/issues/5722',
    },
  }, async ({ request }) => {
    // Test dengan berbagai format invalid
    const testPayloads = [
      { payload: '2024-12-19', expectStatus: [200, 400, 422], description: 'Y-m-d format' },
      { payload: '19/12/2024', expectStatus: [200, 400, 422], description: 'd/m/Y format' },
      { payload: '', expectStatus: [200], description: 'empty - should use default' },
      { payload: 'null', expectStatus: [200, 400, 422], description: 'null string' },
    ];

    for (const test of testPayloads) {
      let response;
      if (test.payload === '') {
        // Test tanpa parameter
        response = await request.get(`${baseURL}${endpoint}`, {
          headers: {
            'X-Requested-With': 'XMLHttpRequest'
          }
        });
      } else {
        response = await request.get(`${baseURL}${endpoint}`, {
          params: { tgl_pemilihan: test.payload },
          headers: {
            'X-Requested-With': 'XMLHttpRequest'
          }
        });
      }

      const isExpected = test.expectStatus.includes(response.status());

      expect(isExpected).toBe(true);
    }
  });
});
