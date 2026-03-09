import { test, expect, request } from '@playwright/test';
import { Laravel } from '../../../utils/laravel';

const BASE_URL = process.env.PLAYWRIGHT_BASE_URL || 'http://127.0.0.1:8000';

// Field yang diizinkan pada response (PemerintahTransformer)
const ALLOWED_FIELDS = [
    'id', 'nama', 'nama_jabatan', 'tupoksi', 'foto', 'media_sosial', 'status_kehadiran', 'kehadiran', 'hari_libur',
];

// Field yang diizinkan pada nested object kehadiran
const ALLOWED_KEHADIRAN_FIELDS = [
    'status_kehadiran', 'jam_masuk', 'jam_keluar', 'tanggal',
];

// Field sensitif yang tidak boleh muncul di response publik
const SENSITIVE_FIELDS = [
    'pamong_nik', 'nik',
    'penduduk',
    'pamong_nip', 'pamong_niap',
    'id_pend', 'jabatan_id', 'config_id',
    'urut', 'pamong_ttd', 'pamong_ub', 'pamong_status',
    'created_at', 'updated_at',
];

async function getPamongId(): Promise<number | null> {
    const rows = await Laravel.select(
        'SELECT pamong_id FROM tweb_desa_pamong WHERE pamong_status = 1 LIMIT 1',
    );
    return rows?.[0]?.pamong_id ?? null;
}

test.describe('ISS-03 — API Pemerintah - Validasi NIK Tidak Terekspos #6052', () => {

    test('GET /internal_api/pemerintah - response hanya mengandung field yang diizinkan', {
        annotation: {
            type: 'issue',
            description: 'https://github.com/OpenSID/premium/issues/6052',
        },
    }, async () => {
        const id = await getPamongId();

        if (!id) {
            test.skip(true, 'Tidak ada data pamong aktif di database');
            return;
        }

        const ctx = await request.newContext({ baseURL: BASE_URL });
        const res = await ctx.get('/internal_api/pemerintah');

        expect(res.status()).toBe(200);
        const body = await res.json();
        expect(body).toHaveProperty('data');
        expect(Array.isArray(body.data)).toBeTruthy();

        for (const item of body.data) {
            const attrs: Record<string, unknown> = item.attributes ?? item;

            // Semua field yang ada harus ada di daftar yang diizinkan
            for (const key of Object.keys(attrs)) {
                expect(ALLOWED_FIELDS, `Field '${key}' tidak seharusnya ada di response`).toContain(key);
            }

            // Field sensitif tidak boleh ada
            for (const field of SENSITIVE_FIELDS) {
                expect(attrs, `Field sensitif '${field}' tidak boleh ada di response`).not.toHaveProperty(field);
            }

            // Jika kehadiran tidak null, hanya boleh mengandung field yang diizinkan
            if (attrs.kehadiran !== null && attrs.kehadiran !== undefined) {
                const kehadiranObj = attrs.kehadiran as Record<string, unknown>;
                for (const key of Object.keys(kehadiranObj)) {
                    expect(ALLOWED_KEHADIRAN_FIELDS, `Field kehadiran '${key}' tidak seharusnya ada`).toContain(key);
                }
            }
        }

        await ctx.dispose();
    });

    test('GET /internal_api/pemerintah - pamong_nik dan NIK tidak terekspos', {
        annotation: {
            type: 'issue',
            description: 'https://github.com/OpenSID/premium/issues/6052',
        },
    }, async () => {
        const ctx = await request.newContext({ baseURL: BASE_URL });
        const res = await ctx.get('/internal_api/pemerintah');

        expect(res.status()).toBe(200);
        const body = await res.json();
        const rawText = await res.text().catch(() => JSON.stringify(body));

        // NIK tidak boleh muncul di response sama sekali (termasuk nested objek)
        for (const item of body.data) {
            const attrs: Record<string, unknown> = item.attributes ?? item;
            expect(attrs).not.toHaveProperty('pamong_nik');
            expect(attrs).not.toHaveProperty('nik');
            expect(attrs).not.toHaveProperty('penduduk');
        }

        await ctx.dispose();
    });

    test('GET /internal_api/pemerintah - response mengandung field wajib', async () => {
        const id = await getPamongId();

        if (!id) {
            test.skip(true, 'Tidak ada data pamong aktif di database');
            return;
        }

        const ctx = await request.newContext({ baseURL: BASE_URL });
        const res = await ctx.get('/internal_api/pemerintah');
        const body = await res.json();

        expect(body.data.length).toBeGreaterThan(0);

        for (const item of body.data) {
            const attrs: Record<string, unknown> = item.attributes ?? item;
            expect(attrs).toHaveProperty('id');
            expect(attrs).toHaveProperty('nama');
            expect(attrs).toHaveProperty('nama_jabatan');
            expect(attrs).toHaveProperty('tupoksi');
            expect(attrs).toHaveProperty('foto');
            expect(attrs).toHaveProperty('media_sosial');
            expect(attrs).toHaveProperty('status_kehadiran');
            expect(attrs).toHaveProperty('kehadiran');
            expect(attrs).toHaveProperty('hari_libur');
        }

        await ctx.dispose();
    });

    test('GET /internal_api/pemerintah - response memiliki struktur pagination', async () => {
        const ctx = await request.newContext({ baseURL: BASE_URL });
        const res = await ctx.get('/internal_api/pemerintah');
        const body = await res.json();

        expect(body).toHaveProperty('meta');
        expect(body.meta).toHaveProperty('pagination');
        expect(body.meta.pagination).toHaveProperty('total');
        expect(body.meta.pagination).toHaveProperty('per_page');
        expect(body.meta.pagination).toHaveProperty('current_page');

        await ctx.dispose();
    });
});
