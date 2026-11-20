// Issue: #7001 — KRITIS: internal_api/peta membocorkan seluruh data penduduk
// (tweb_penduduk.*) + koordinat rumah tanpa autentikasi
// https://github.com/OpenSID/premium/issues/7001
import { test, expect, request } from '../../../utils/regressionTest';
import { Laravel } from '../../../utils/laravel';

const BASE_URL = process.env.PLAYWRIGHT_BASE_URL || 'http://127.0.0.1:8000';

// Kunci payload yang diizinkan pada response (PetaTransformer::ALLOWED_KEYS)
const ALLOWED_KEYS = [
    'id', 'desa', 'wilayah', 'dusun_gis', 'rw_gis', 'rt_gis',
    'lokasi', 'garis', 'area', 'lokasi_pembangunan', 'persil',
    'list_ref', 'list_bantuan', 'list_dusun', 'title',
    'pengaturan', 'tampilkan_cdesa', 'covid',
];

// Kolom identitas desa yang diizinkan tampil (PetaTransformer::DESA_FIELDS)
const ALLOWED_DESA_FIELDS = ['nama_desa', 'kode_desa', 'path', 'lat', 'lng', 'zoom'];

// Field pribadi warga yang tidak boleh muncul di manapun dalam response publik
const SENSITIVE_FIELDS = [
    'penduduk', 'nik', 'no_kk', 'tanggallahir', 'tempatlahir',
    'nama_ayah', 'nama_ibu', 'ayah_nik', 'ibu_nik', 'telepon', 'email',
    'telegram', 'tag_id_card', 'no_asuransi', 'id_asuransi',
    'dokumen_pasport', 'dokumen_kitas', 'alamat_sekarang', 'alamat_sebelumnya',
    'akta_lahir', 'akta_perkawinan', 'akta_perceraian', 'golongan_darah_id',
];

async function aktifkanMenuPeta(): Promise<void> {
    await Laravel.query("UPDATE menu SET enabled = 1 WHERE link = 'peta'");
}

test.describe('API Peta - Tidak Mengekspos Data Penduduk Individu #7001', () => {
    test.beforeAll(async () => {
        await aktifkanMenuPeta();
    });

    test('GET /internal_api/peta - payload tidak memuat data penduduk', {
        annotation: {
            type: 'issue',
            description: 'https://github.com/OpenSID/premium/issues/7001',
        },
    }, async () => {
        const ctx = await request.newContext({ baseURL: BASE_URL });
        const res = await ctx.get('/internal_api/peta');

        expect(res.status()).toBe(200);
        const body = await res.json();
        expect(body).toHaveProperty('data');

        const attrs: Record<string, unknown> = body.data[0].attributes ?? body.data[0];

        // Seluruh kunci payload harus ada di whitelist
        for (const key of Object.keys(attrs)) {
            expect(ALLOWED_KEYS, `Kunci '${key}' tidak seharusnya ada di response`).toContain(key);
        }

        // Layer penduduk tidak boleh ada sama sekali
        expect(attrs).not.toHaveProperty('penduduk');

        // Identitas desa hanya boleh berisi kolom yang diizinkan
        if (attrs.desa && typeof attrs.desa === 'object') {
            for (const key of Object.keys(attrs.desa as Record<string, unknown>)) {
                expect(ALLOWED_DESA_FIELDS, `Kolom desa '${key}' tidak seharusnya ada`).toContain(key);
            }
        }

        await ctx.dispose();
    });

    test('GET /internal_api/peta - tidak ada field pribadi warga di seluruh response', {
        annotation: {
            type: 'issue',
            description: 'https://github.com/OpenSID/premium/issues/7001',
        },
    }, async () => {
        const ctx = await request.newContext({ baseURL: BASE_URL });
        const res = await ctx.get('/internal_api/peta');

        expect(res.status()).toBe(200);
        const raw = await res.text();

        for (const field of SENSITIVE_FIELDS) {
            expect(raw, `Field sensitif '${field}' bocor di /internal_api/peta`).not.toContain(`"${field}"`);
        }

        await ctx.dispose();
    });

    test('POST /internal_api/peta - juga tidak mengekspos data penduduk', async () => {
        const ctx = await request.newContext({ baseURL: BASE_URL });
        const res = await ctx.post('/internal_api/peta');

        expect(res.status()).toBe(200);
        const body = await res.json();
        const attrs: Record<string, unknown> = body.data[0].attributes ?? body.data[0];

        expect(attrs).not.toHaveProperty('penduduk');

        await ctx.dispose();
    });

    test('GET /internal_api/peta - mengembalikan 404 saat menu peta tidak aktif', async () => {
        await Laravel.query("UPDATE menu SET enabled = 0 WHERE link = 'peta'");

        try {
            const ctx = await request.newContext({ baseURL: BASE_URL });
            const res = await ctx.get('/internal_api/peta');
            expect(res.status()).toBe(404);
            await ctx.dispose();
        } finally {
            await aktifkanMenuPeta();
        }
    });
});
