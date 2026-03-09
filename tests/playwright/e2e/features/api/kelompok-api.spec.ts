import { test, expect, request } from '@playwright/test';
import { Laravel } from '../../../utils/laravel';

const BASE_URL = process.env.PLAYWRIGHT_BASE_URL || 'http://127.0.0.1:8000';

// Field yang diizinkan pada response detail (KelompokTransformer)
const ALLOWED_DETAIL_FIELDS = [
  'nama', 'kode', 'kategori', 'tipe', 'no_sk_pendirian',
  'keterangan', 'logo', 'nama_ketua', 'slug',
];

// Field yang diizinkan pada response anggota (KelompokAnggotaTransformer)
const ALLOWED_ANGGOTA_FIELDS = [
  'no_anggota', 'nama_jabatan', 'nama_penduduk',
  'alamat_lengkap', 'sex', 'foto',
];

// Field sensitif yang tidak boleh muncul pada response anggota
const SENSITIVE_ANGGOTA_FIELDS = [
  'config_id', 'id_penduduk', 'nik_luar', 'tanggallahir_luar', 'agama_luar',
  'pendidikan_luar', 'no_sk_jabatan', 'nmr_sk_pengangkatan', 'nmr_sk_pemberhentian',
  'tgl_sk_pengangkatan', 'tgl_sk_pemberhentian', 'anggota',
];

// Field sensitif yang tidak boleh muncul pada response detail
const SENSITIVE_DETAIL_FIELDS = [
  'config_id', 'id_ketua', 'id_master', 'created_at', 'updated_at',
  'created_by', 'updated_by', 'status',
];

async function getSlug(tipe: 'kelompok' | 'lembaga'): Promise<string | null> {
  const rows = await Laravel.select(
    'SELECT slug FROM kelompok WHERE tipe = ? AND slug IS NOT NULL LIMIT 1',
    [tipe]
  );
  return rows?.[0]?.slug ?? null;
}

test.describe('API Kelompok/Lembaga - Validasi Response Tidak Sensitif', () => {

  // ─── KELOMPOK DETAIL ────────────────────────────────────────────────────────

  test.describe('GET /internal_api/kelompok/{slug} - detail kelompok', () => {
    test('response hanya mengandung field yang diizinkan', async () => {
      const slug = await getSlug('kelompok');

      if (!slug) {
        test.skip(true, 'Tidak ada data kelompok di database');
        return;
      }

      const ctx = await request.newContext({ baseURL: BASE_URL });
      const res = await ctx.get(`/internal_api/kelompok/${slug}`);

      expect(res.status()).toBe(200);
      const body = await res.json();
      expect(body).toHaveProperty('data');

      const attrs: Record<string, unknown> = body.data.attributes ?? body.data;

      // Semua field yang ada harus ada di daftar yang diizinkan
      for (const key of Object.keys(attrs)) {
        expect(ALLOWED_DETAIL_FIELDS, `Field '${key}' tidak seharusnya ada di response`).toContain(key);
      }

      // Field sensitif tidak boleh ada
      for (const field of SENSITIVE_DETAIL_FIELDS) {
        expect(attrs, `Field sensitif '${field}' tidak boleh ada di response`).not.toHaveProperty(field);
      }

      await ctx.dispose();
    });

    test('response mengandung field wajib', async () => {
      const slug = await getSlug('kelompok');

      if (!slug) {
        test.skip(true, 'Tidak ada data kelompok di database');
        return;
      }

      const ctx = await request.newContext({ baseURL: BASE_URL });
      const res = await ctx.get(`/internal_api/kelompok/${slug}`);
      const body = await res.json();
      const attrs: Record<string, unknown> = body.data.attributes ?? body.data;

      expect(attrs).toHaveProperty('nama');
      expect(attrs).toHaveProperty('tipe');
      expect(attrs).toHaveProperty('slug');
      expect(attrs).toHaveProperty('nama_ketua');

      await ctx.dispose();
    });

    test('slug tidak valid mengembalikan response kosong atau 404', async () => {
      const ctx = await request.newContext({ baseURL: BASE_URL });
      const res = await ctx.get('/internal_api/kelompok/slug-tidak-ada-sama-sekali-xyz');

      expect([200, 404]).toContain(res.status());

      if (res.status() === 200) {
        const body = await res.json();
        // Data harus null atau kosong
        expect(body.data == null || body.data === '').toBeTruthy();
      }

      await ctx.dispose();
    });
  });

  // ─── KELOMPOK ANGGOTA ────────────────────────────────────────────────────────

  test.describe('GET /internal_api/kelompok/anggota/{slug} - anggota kelompok', () => {
    test('response hanya mengandung field yang diizinkan pada tiap anggota', async () => {
      const slug = await getSlug('kelompok');

      if (!slug) {
        test.skip(true, 'Tidak ada data kelompok di database');
        return;
      }

      const ctx = await request.newContext({ baseURL: BASE_URL });
      const res = await ctx.get(`/internal_api/kelompok/anggota/${slug}`);

      expect(res.status()).toBe(200);
      const body = await res.json();
      expect(body).toHaveProperty('data');
      expect(Array.isArray(body.data)).toBeTruthy();

      for (const item of body.data) {
        const attrs: Record<string, unknown> = item.attributes ?? item;

        // Semua field yang ada harus ada di daftar yang diizinkan
        for (const key of Object.keys(attrs)) {
          expect(ALLOWED_ANGGOTA_FIELDS, `Field '${key}' tidak seharusnya ada di response anggota`).toContain(key);
        }

        // Field sensitif tidak boleh ada
        for (const field of SENSITIVE_ANGGOTA_FIELDS) {
          expect(attrs, `Field sensitif '${field}' tidak boleh ada di response anggota`).not.toHaveProperty(field);
        }
      }

      await ctx.dispose();
    });

    test('response mengandung field wajib pada tiap anggota', async () => {
      const slug = await getSlug('kelompok');

      if (!slug) {
        test.skip(true, 'Tidak ada data kelompok di database');
        return;
      }

      const ctx = await request.newContext({ baseURL: BASE_URL });
      const res = await ctx.get(`/internal_api/kelompok/anggota/${slug}`);
      const body = await res.json();

      if (body.data.length === 0) {
        test.skip(true, 'Kelompok tidak memiliki anggota');
        return;
      }

      for (const item of body.data) {
        const attrs: Record<string, unknown> = item.attributes ?? item;
        expect(attrs).toHaveProperty('no_anggota');
        expect(attrs).toHaveProperty('nama_jabatan');
        expect(attrs).toHaveProperty('nama_penduduk');
        expect(attrs).toHaveProperty('alamat_lengkap');
        expect(attrs).toHaveProperty('sex');
      }

      await ctx.dispose();
    });

    test('response memiliki struktur pagination', async () => {
      const slug = await getSlug('kelompok');

      if (!slug) {
        test.skip(true, 'Tidak ada data kelompok di database');
        return;
      }

      const ctx = await request.newContext({ baseURL: BASE_URL });
      const res = await ctx.get(`/internal_api/kelompok/anggota/${slug}`);
      const body = await res.json();

      expect(body).toHaveProperty('meta');
      expect(body.meta).toHaveProperty('pagination');
      expect(body.meta.pagination).toHaveProperty('total');
      expect(body.meta.pagination).toHaveProperty('per_page');
      expect(body.meta.pagination).toHaveProperty('current_page');

      await ctx.dispose();
    });
  });

  // ─── LEMBAGA DETAIL ─────────────────────────────────────────────────────────

  test.describe('GET /internal_api/lembaga/{slug} - detail lembaga', () => {
    test('response hanya mengandung field yang diizinkan', async () => {
      const slug = await getSlug('lembaga');

      if (!slug) {
        test.skip(true, 'Tidak ada data lembaga di database');
        return;
      }

      const ctx = await request.newContext({ baseURL: BASE_URL });
      const res = await ctx.get(`/internal_api/lembaga/${slug}`);

      expect(res.status()).toBe(200);
      const body = await res.json();
      const attrs: Record<string, unknown> = body.data.attributes ?? body.data;

      for (const key of Object.keys(attrs)) {
        expect(ALLOWED_DETAIL_FIELDS, `Field '${key}' tidak seharusnya ada di response lembaga`).toContain(key);
      }

      for (const field of SENSITIVE_DETAIL_FIELDS) {
        expect(attrs, `Field sensitif '${field}' tidak boleh ada di response lembaga`).not.toHaveProperty(field);
      }

      await ctx.dispose();
    });

    test('field tipe bernilai "Lembaga"', async () => {
      const slug = await getSlug('lembaga');

      if (!slug) {
        test.skip(true, 'Tidak ada data lembaga di database');
        return;
      }

      const ctx = await request.newContext({ baseURL: BASE_URL });
      const res = await ctx.get(`/internal_api/lembaga/${slug}`);
      const body = await res.json();
      const attrs: Record<string, unknown> = body.data.attributes ?? body.data;

      expect(attrs.tipe).toBe('Lembaga');

      await ctx.dispose();
    });
  });

  // ─── LEMBAGA ANGGOTA ─────────────────────────────────────────────────────────

  test.describe('GET /internal_api/lembaga/anggota/{slug} - anggota lembaga', () => {
    test('response hanya mengandung field yang diizinkan pada tiap anggota', async () => {
      const slug = await getSlug('lembaga');

      if (!slug) {
        test.skip(true, 'Tidak ada data lembaga di database');
        return;
      }

      const ctx = await request.newContext({ baseURL: BASE_URL });
      const res = await ctx.get(`/internal_api/lembaga/anggota/${slug}`);

      expect(res.status()).toBe(200);
      const body = await res.json();
      expect(Array.isArray(body.data)).toBeTruthy();

      for (const item of body.data) {
        const attrs: Record<string, unknown> = item.attributes ?? item;

        for (const key of Object.keys(attrs)) {
          expect(ALLOWED_ANGGOTA_FIELDS, `Field '${key}' tidak seharusnya ada di response anggota lembaga`).toContain(key);
        }

        for (const field of SENSITIVE_ANGGOTA_FIELDS) {
          expect(attrs, `Field sensitif '${field}' tidak boleh ada di response anggota lembaga`).not.toHaveProperty(field);
        }
      }

      await ctx.dispose();
    });

    test('field sex menampilkan teks bukan angka', async () => {
      const slug = await getSlug('lembaga');

      if (!slug) {
        test.skip(true, 'Tidak ada data lembaga di database');
        return;
      }

      const ctx = await request.newContext({ baseURL: BASE_URL });
      const res = await ctx.get(`/internal_api/lembaga/anggota/${slug}`);
      const body = await res.json();

      for (const item of body.data) {
        const attrs: Record<string, unknown> = item.attributes ?? item;

        if (attrs.sex !== null && attrs.sex !== undefined) {
          expect(typeof attrs.sex).toBe('string');
          expect(['LAKI-LAKI', 'PEREMPUAN']).toContain(attrs.sex);
        }
      }

      await ctx.dispose();
    });
  });
});
