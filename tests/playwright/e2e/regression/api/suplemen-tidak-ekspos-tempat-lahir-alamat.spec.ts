// Issue: #6999 — Keamanan: tempat lahir & alamat penduduk (menu Suplemen)
// tampil di halaman/API publik tanpa login.
// Endpoint publik tanpa auth (donjo-app/Routes/api.php grup internal_api):
//   GET/POST /internal_api/suplemen                -> Suplemen@index   (daftar)
//   GET/POST /internal_api/suplemen/{slug}         -> Suplemen@detail  (satu suplemen)
//   GET/POST /internal_api/suplemen/anggota/{slug} -> Suplemen@anggota (daftar terdata)
// Sebelum perbaikan response anggota memuat attributes.tempatlahir dan alamat
// lengkap (nama jalan/nomor rumah KK), dan data tetap tersaji walau menu suplemen
// tidak dipublikasikan admin.
// https://github.com/OpenSID/premium/issues/6999
import { test, expect, request } from '../../../utils/regressionTest';
import { Laravel } from '../../../utils/laravel';

const BASE_URL = process.env.PLAYWRIGHT_BASE_URL || 'http://127.0.0.1:8000';

// Kunci payload yang diizinkan pada tiap baris terdata (SuplemenTerdataTransformer).
const ALLOWED_KEYS = ['id', 'terdata_nama', 'sex', 'alamat'];

// Kunci payload yang diizinkan pada detail suplemen (SuplemenTransformer).
const ALLOWED_DETAIL_KEYS = ['id', 'nama', 'sasaran', 'nama_sasaran', 'keterangan', 'slug'];

// Field pribadi warga yang tidak boleh muncul di manapun pada response publik.
const SENSITIVE_FIELDS = [
    'tempatlahir', 'tanggallahir', 'nik', 'no_kk', 'alamat_sekarang',
    'alamat_sebelumnya', 'nama_ayah', 'nama_ibu',
];

interface SuplemenAktif {
    id: number;
    slug: string;
    link: string;
}

// Cari suplemen yang menunya dipublikasikan admin (menu aktif) dan punya data terdata.
async function cariSuplemenAktif(): Promise<SuplemenAktif | null> {
    const rows = await Laravel.select(
        `SELECT s.id AS id, s.slug AS slug, m.link AS link
           FROM suplemen s
           JOIN menu m ON m.link = CONCAT('data-suplemen/', s.id) AND m.enabled = 1
          WHERE s.slug IS NOT NULL
            AND EXISTS (SELECT 1 FROM suplemen_terdata st WHERE st.id_suplemen = s.id)
          LIMIT 1`
    );
    return (rows?.[0] as SuplemenAktif) ?? null;
}

test.describe('API Suplemen - Tidak Mengekspos Tempat Lahir & Alamat Lengkap #6999', () => {
    test('GET /internal_api/suplemen/{slug} - payload detail di-whitelist', {
        annotation: { type: 'issue', description: 'https://github.com/OpenSID/premium/issues/6999' },
    }, async () => {
        const suplemen = await cariSuplemenAktif();
        if (!suplemen) {
            test.skip(true, 'Tidak ada suplemen dengan menu aktif + data terdata di database uji');
            return;
        }

        const ctx = await request.newContext({ baseURL: BASE_URL });
        const res = await ctx.get(`/internal_api/suplemen/${suplemen.slug}`);

        expect(res.status()).toBe(200);
        const body = await res.json();
        expect(body).toHaveProperty('data');

        const attrs: Record<string, unknown> = body.data.attributes ?? body.data;
        for (const key of Object.keys(attrs)) {
            expect(ALLOWED_DETAIL_KEYS, `Kunci '${key}' tidak seharusnya ada di detail suplemen`).toContain(key);
        }

        await ctx.dispose();
    });

    test('GET /internal_api/suplemen/anggota/{slug} - payload terdata di-whitelist, tanpa tempat lahir', {
        annotation: { type: 'issue', description: 'https://github.com/OpenSID/premium/issues/6999' },
    }, async () => {
        const suplemen = await cariSuplemenAktif();
        if (!suplemen) {
            test.skip(true, 'Tidak ada suplemen dengan menu aktif + data terdata di database uji');
            return;
        }

        const ctx = await request.newContext({ baseURL: BASE_URL });
        const res = await ctx.get(`/internal_api/suplemen/anggota/${suplemen.slug}?page[size]=25`);

        expect(res.status()).toBe(200);
        const raw = await res.text();
        const body = JSON.parse(raw);
        expect(body).toHaveProperty('data');
        expect(Array.isArray(body.data)).toBeTruthy();

        for (const item of body.data) {
            const attrs: Record<string, unknown> = item.attributes ?? item;

            for (const key of Object.keys(attrs)) {
                expect(ALLOWED_KEYS, `Kunci '${key}' tidak seharusnya ada di response suplemen`).toContain(key);
            }

            // Alamat dibatasi hanya RT/RW + dusun.
            if (typeof attrs.alamat === 'string' && attrs.alamat.trim() !== '') {
                expect(attrs.alamat as string).toContain('RT/RW');
            }
        }

        for (const field of SENSITIVE_FIELDS) {
            expect(raw, `Field sensitif '${field}' bocor di /internal_api/suplemen`).not.toContain(`"${field}"`);
        }

        await ctx.dispose();
    });

    test('GET /internal_api/suplemen/anggota/{slug} - sort berdasarkan tempat lahir ditolak', async () => {
        const suplemen = await cariSuplemenAktif();
        if (!suplemen) {
            test.skip(true, 'Tidak ada suplemen dengan menu aktif + data terdata di database uji');
            return;
        }

        const ctx = await request.newContext({ baseURL: BASE_URL });
        const res = await ctx.get(`/internal_api/suplemen/anggota/${suplemen.slug}?sort=tweb_penduduk.tempatlahir`);

        // Spatie QueryBuilder menolak sort di luar allowedSorts (HTTP 4xx).
        expect(res.status()).toBeGreaterThanOrEqual(400);
        if (res.status() === 200) {
            const raw = await res.text();
            expect(raw).not.toContain('"tempatlahir"');
        }

        await ctx.dispose();
    });

    test('menu suplemen tidak dipublikasikan -> detail & anggota 404', {
        annotation: { type: 'issue', description: 'https://github.com/OpenSID/premium/issues/6999' },
    }, async () => {
        const suplemen = await cariSuplemenAktif();
        if (!suplemen) {
            test.skip(true, 'Tidak ada suplemen dengan menu aktif + data terdata di database uji');
            return;
        }

        await Laravel.query('UPDATE menu SET enabled = 0 WHERE link = ?', [suplemen.link]);
        try {
            const ctx = await request.newContext({ baseURL: BASE_URL });
            const anggota = await ctx.get(`/internal_api/suplemen/anggota/${suplemen.slug}?page[size]=5`);
            expect(anggota.status()).toBe(404);
            const detail = await ctx.get(`/internal_api/suplemen/${suplemen.slug}`);
            expect(detail.status()).toBe(404);
            await ctx.dispose();
        } finally {
            await Laravel.query('UPDATE menu SET enabled = 1 WHERE link = ?', [suplemen.link]);
        }
    });

    test('GET /internal_api/suplemen/anggota/{slug} - 404 untuk slug tidak dikenal', async () => {
        const ctx = await request.newContext({ baseURL: BASE_URL });
        const res = await ctx.get('/internal_api/suplemen/anggota/slug-tidak-ada-xyz?page[size]=5');
        expect(res.status()).toBe(404);
        await ctx.dispose();
    });
});
