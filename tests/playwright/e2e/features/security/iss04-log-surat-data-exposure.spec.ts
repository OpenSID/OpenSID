import { test, expect } from '@playwright/test';

/**
 * ISS-04 — Seluruh Field Log Surat Terekspos Tanpa Batasan
 *
 * Verifikasi bahwa endpoint GET /internal_api/verifikasi-surat dan
 * GET /internal_api/verifikasi-surat-dinas hanya mengembalikan field
 * yang diizinkan (whitelist) dan tidak mengekspos data internal/sensitif.
 *
 * @see https://github.com/OpenSID/premium/issues/6060
 */

const ALLOWED_FIELDS = ['id', 'nomor_surat', 'perihal', 'nama_penduduk', 'pamong_nama', 'pamong_jabatan', 'tanggal', 'pdf'] as const;

const SENSITIVE_FIELDS = [
  'config_id',
  'id_pend',
  'nama_surat',
  'id_format_surat',
  'id_pamong',
  'isi_surat',
  'masa_berlaku',
  'no_kk_pend',
  'keterangan',
  'id_pengikut',
  'syarat_surat',
  'created_by',
  'updated_by',
  'deleted_at',
  'qr_code',
];

test.describe('Security ISS-04: Log Surat field exposure', () => {
  test('GET /internal_api/verifikasi-surat tidak mengekspos field sensitif', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/premium/issues/6060',
    },
  }, async ({ request, baseURL }) => {
    const response = await request.get(`${baseURL}/api/internal_api/verifikasi-surat`);

    // Endpoint harus merespons dengan sukses
    expect(response.status()).toBe(200);

    const body = await response.json();

    // Jika tidak ada data, skip verifikasi field (data kosong adalah valid)
    if (!body.data || body.data.length === 0) {
      return;
    }

    const attributes = body.data[0].attributes as Record<string, unknown>;

    // Verifikasi field sensitif TIDAK ada di response
    for (const field of SENSITIVE_FIELDS) {
      expect(
        Object.prototype.hasOwnProperty.call(attributes, field),
        `Field sensitif "${field}" seharusnya tidak ada di response attributes`,
      ).toBe(false);
    }

    // Verifikasi hanya field whitelist yang ada
    for (const key of Object.keys(attributes)) {
      expect(
        (ALLOWED_FIELDS as readonly string[]).includes(key),
        `Field "${key}" tidak ada di whitelist yang diizinkan`,
      ).toBe(true);
    }
  });

  test('GET /internal_api/verifikasi-surat-dinas tidak mengekspos field sensitif', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/premium/issues/6060',
    },
  }, async ({ request, baseURL }) => {
    const response = await request.get(`${baseURL}/api/internal_api/verifikasi-surat-dinas`);

    expect(response.status()).toBe(200);

    const body = await response.json();

    if (!body.data || body.data.length === 0) {
      return;
    }

    const attributes = body.data[0].attributes as Record<string, unknown>;

    for (const field of SENSITIVE_FIELDS) {
      expect(
        Object.prototype.hasOwnProperty.call(attributes, field),
        `Field sensitif "${field}" seharusnya tidak ada di response attributes`,
      ).toBe(false);
    }

    // verifikasi-surat-dinas tidak memiliki field pdf
    const ALLOWED_DINAS = ALLOWED_FIELDS.filter((f) => f !== 'pdf');
    for (const key of Object.keys(attributes)) {
      expect(
        (ALLOWED_DINAS as readonly string[]).includes(key),
        `Field "${key}" tidak ada di whitelist yang diizinkan`,
      ).toBe(true);
    }
  });

  test('GET /internal_api/verifikasi-surat menolak sparse fieldset untuk field sensitif', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/premium/issues/6060',
    },
  }, async ({ request, baseURL }) => {
    // Coba minta field sensitif via sparse fieldset JSON:API
    const response = await request.get(
      `${baseURL}/api/internal_api/verifikasi-surat?fields[log_surat]=id,nama_surat,isi_surat,id_pend`,
    );

    // Endpoint harus tetap merespons (tidak crash)
    expect(response.status()).toBe(200);

    const body = await response.json();

    if (!body.data || body.data.length === 0) {
      return;
    }

    const attributes = body.data[0].attributes as Record<string, unknown>;

    // Field sensitif tetap tidak boleh muncul meskipun diminta
    expect(Object.prototype.hasOwnProperty.call(attributes, 'nama_surat')).toBe(false);
    expect(Object.prototype.hasOwnProperty.call(attributes, 'isi_surat')).toBe(false);
    expect(Object.prototype.hasOwnProperty.call(attributes, 'id_pend')).toBe(false);
  });

  test('GET /internal_api/verifikasi-surat-dinas menolak sparse fieldset untuk field sensitif', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/premium/issues/6060',
    },
  }, async ({ request, baseURL }) => {
    const response = await request.get(
      `${baseURL}/api/internal_api/verifikasi-surat-dinas?fields[log_surat_dinas]=id,nama_surat,id_pamong,keterangan`,
    );

    expect(response.status()).toBe(200);

    const body = await response.json();

    if (!body.data || body.data.length === 0) {
      return;
    }

    const attributes = body.data[0].attributes as Record<string, unknown>;

    expect(Object.prototype.hasOwnProperty.call(attributes, 'nama_surat')).toBe(false);
    expect(Object.prototype.hasOwnProperty.call(attributes, 'id_pamong')).toBe(false);
    expect(Object.prototype.hasOwnProperty.call(attributes, 'keterangan')).toBe(false);
  });
});
