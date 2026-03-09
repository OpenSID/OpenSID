import { test, expect } from '@playwright/test';

/**
 * Test untuk Issue #6058 (ISS-01): NIK & Data Pribadi Penduduk DPT Terbuka Publik
 *
 * Bug: Endpoint publik GET /internal_api/dpt mengekspos field sensitif
 * nik, tanggallahir, tempatlahir, ayah_nik, ibu_nik, telepon, email,
 * no_asuransi, bpjs_ketenagakerjaan, tag_id_card, email_token, telegram_token
 * melalui DptTransformer::transform() yang sebelumnya memanggil $dpt->toArray().
 */
test.describe('Issue #6058 (ISS-01) - Kebocoran NIK & Data Pribadi pada Endpoint Publik DPT', () => {

  test('response tidak boleh mengandung field sensitif penduduk', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/premium/issues/6058',
    },
  }, async ({ request }) => {
    const response = await request.get('/internal_api/dpt');

    expect(response.status()).toBe(200);

    const body = await response.json();
    const data: any[] = body?.data ?? [];

    if (data.length === 0) {
      test.skip(true, 'Tidak ada data DPT untuk diuji');
      return;
    }

    const attrs = data[0]?.attributes ?? data[0];

    // ----------------------------------------------------------------
    // Field sensitif TIDAK boleh ada di response
    // ----------------------------------------------------------------
    expect(attrs).not.toHaveProperty('nik');
    expect(attrs).not.toHaveProperty('tanggallahir');
    expect(attrs).not.toHaveProperty('tempatlahir');
    expect(attrs).not.toHaveProperty('ayah_nik');
    expect(attrs).not.toHaveProperty('ibu_nik');
    expect(attrs).not.toHaveProperty('telepon');
    expect(attrs).not.toHaveProperty('email');
    expect(attrs).not.toHaveProperty('no_asuransi');
    expect(attrs).not.toHaveProperty('bpjs_ketenagakerjaan');
    expect(attrs).not.toHaveProperty('tag_id_card');
    expect(attrs).not.toHaveProperty('email_token');
    expect(attrs).not.toHaveProperty('telegram_token');

    // ----------------------------------------------------------------
    // Field agregat summary HARUS ada
    // ----------------------------------------------------------------
    expect(attrs).toHaveProperty('dusun');
    expect(attrs).toHaveProperty('rw');
    expect(attrs).toHaveProperty('sex');
    expect(attrs).toHaveProperty('total');
  });

  test('response hanya mengandung field whitelist yang diizinkan', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/premium/issues/6058',
    },
  }, async ({ request }) => {
    const response = await request.get('/internal_api/dpt');
    expect(response.status()).toBe(200);

    const body = await response.json();
    const data: any[] = body?.data ?? [];

    if (data.length === 0) {
      test.skip(true, 'Tidak ada data DPT untuk diuji');
      return;
    }

    const attrs = data[0]?.attributes ?? data[0];
    const allowedKeys = new Set(['id', 'dusun', 'rw', 'sex', 'total']);

    // Semua key yang dikembalikan harus masuk dalam whitelist
    for (const key of Object.keys(attrs)) {
      expect(allowedKeys.has(key), `Field "${key}" tidak seharusnya ada di response`).toBe(true);
    }
  });

  test('sparse fieldset sensitif harus diabaikan', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/premium/issues/6058',
    },
  }, async ({ request }) => {
    // Coba paksa minta field sensitif via sparse fieldset
    const response = await request.get('/internal_api/dpt', {
      params: { 'fields[penduduk]': 'id,nik,tanggallahir,telepon,email,ayah_nik' },
    });

    expect(response.status()).toBe(200);

    const body = await response.json();
    const data: any[] = body?.data ?? [];

    if (data.length === 0) {
      test.skip(true, 'Tidak ada data DPT untuk diuji');
      return;
    }

    const attrs = data[0]?.attributes ?? data[0];

    // Field sensitif TIDAK boleh ada meski diminta eksplisit
    expect(attrs).not.toHaveProperty('nik');
    expect(attrs).not.toHaveProperty('tanggallahir');
    expect(attrs).not.toHaveProperty('telepon');
    expect(attrs).not.toHaveProperty('email');
    expect(attrs).not.toHaveProperty('ayah_nik');
  });

});
