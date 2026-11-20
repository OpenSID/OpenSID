// Issue: #7020 — Data sensitif author terekspos di endpoint publik
// GET/POST /internal_api/arsip — PII operator desa + material OTP/telegram
// bocor tanpa autentikasi (ArtikelTransformer::transform() dulu `return $artikel->toArray()`
// mentah, mengikutkan relasi author = App\Models\User::toArray()).
// https://github.com/OpenSID/premium/issues/7020
import { test, expect, request } from '../../../utils/regressionTest';
import { Laravel } from '../../../utils/laravel';

const BASE_URL = process.env.PLAYWRIGHT_BASE_URL || 'http://127.0.0.1:8000';

// Kunci payload artikel yang diizinkan (ArtikelTransformer::ALLOWED_KEYS)
const ALLOWED_KEYS = [
    'id', 'judul', 'isi', 'gambar', 'gambar1', 'gambar2', 'gambar3',
    'tgl_upload', 'tgl_upload_local', 'slug', 'url_slug', 'hit',
    'enabled', 'headline', 'boleh_komentar', 'tipe', 'id_kategori',
    'author', 'category',
];

// Author hanya boleh berisi nama penulis (ArtikelTransformer::AUTHOR_FIELDS)
const ALLOWED_AUTHOR_FIELDS = ['nama'];

// Kolom user yang tidak boleh muncul di manapun pada response publik
const SENSITIVE_FIELDS = [
    'email', 'phone', 'password', 'otp_identifier', 'otp_channel', 'otp_enabled',
    'telegram_chat_id', 'id_telegram', 'token', 'token_exp', 'session',
    'akses_wilayah', 'batasi_wilayah', 'last_login', 'id_grup', 'pamong_id',
    'email_verified_at', 'telegram_verified_at', 'two_factor_enabled',
    'remember_token', 'company', 'notif_telegram',
];

// Penanda unik yang ditanam ke kolom sensitif admin untuk memastikan nilainya
// benar-benar tidak muncul di response (bukan sekadar cek nama kunci).
const MARKER = 'PII_LEAK_MARKER_7020';

async function pastikanAdaArtikelAktif(): Promise<void> {
    const rows = await Laravel.select(
        "SELECT id FROM artikel WHERE enabled = 1 AND tgl_upload < NOW() LIMIT 1"
    );
    if (rows.length === 0) {
        const admin = await Laravel.user('admin');
        await Laravel.query(
            "INSERT INTO artikel (config_id, isi, enabled, tgl_upload, judul, id_user, tipe) VALUES (?, ?, 1, DATE_SUB(NOW(), INTERVAL 1 DAY), ?, ?, 'dinamis')",
            [admin.config_id, 'Isi artikel uji regresi #7020', 'Artikel Uji Regresi 7020', admin.id]
        );
    }
}

test.describe('API Arsip - Tidak Mengekspos PII Author #7020', () => {
    let adminEmailAsli: unknown = null;

    test.beforeAll(async () => {
        await pastikanAdaArtikelAktif();
        const admin = await Laravel.user('admin');
        adminEmailAsli = admin.email;
        await Laravel.query(
            "UPDATE user SET email = ?, otp_identifier = ?, telegram_chat_id = ? WHERE username = 'admin'",
            [`${MARKER}@example.test`, MARKER, MARKER]
        );
    });

    test.afterAll(async () => {
        await Laravel.query('UPDATE user SET email = ? WHERE username = ?', [adminEmailAsli, 'admin']);
    });

    test('GET /internal_api/arsip - author hanya berisi nama, payload di-whitelist', {
        annotation: { type: 'issue', description: 'https://github.com/OpenSID/premium/issues/7020' },
    }, async () => {
        const ctx = await request.newContext({ baseURL: BASE_URL });
        const res = await ctx.get('/internal_api/arsip?page[size]=5');

        expect(res.status()).toBe(200);
        const body = await res.json();
        expect(body).toHaveProperty('data');
        expect(Array.isArray(body.data)).toBeTruthy();
        expect(body.data.length).toBeGreaterThan(0);

        for (const item of body.data) {
            const attrs: Record<string, unknown> = item.attributes ?? item;

            for (const key of Object.keys(attrs)) {
                expect(ALLOWED_KEYS, `Kunci '${key}' tidak seharusnya ada di response arsip`).toContain(key);
            }

            const author = attrs.author as Record<string, unknown> | null | undefined;
            if (author && typeof author === 'object') {
                for (const key of Object.keys(author)) {
                    expect(ALLOWED_AUTHOR_FIELDS, `Kolom author '${key}' tidak seharusnya ada`).toContain(key);
                }
            }
        }

        await ctx.dispose();
    });

    test('GET /internal_api/arsip - tidak ada kolom/nilai sensitif user di seluruh response', async () => {
        const ctx = await request.newContext({ baseURL: BASE_URL });
        const res = await ctx.get('/internal_api/arsip?page[size]=50');

        expect(res.status()).toBe(200);
        const raw = await res.text();

        for (const field of SENSITIVE_FIELDS) {
            expect(raw, `Kolom sensitif '${field}' bocor di /internal_api/arsip`).not.toContain(`"${field}"`);
        }
        expect(raw, 'Nilai sensitif admin bocor di /internal_api/arsip').not.toContain(MARKER);

        await ctx.dispose();
    });

    test('POST /internal_api/arsip - juga tidak mengekspos PII author', async () => {
        const ctx = await request.newContext({ baseURL: BASE_URL });
        const res = await ctx.post('/internal_api/arsip?page[size]=5');

        expect(res.status()).toBe(200);
        const raw = await res.text();
        for (const field of SENSITIVE_FIELDS) {
            expect(raw, `Kolom sensitif '${field}' bocor di POST /internal_api/arsip`).not.toContain(`"${field}"`);
        }
        expect(raw).not.toContain(MARKER);

        await ctx.dispose();
    });

    test('GET /internal_api/arsip - sparse fieldset kolom sensitif ditolak', async () => {
        const ctx = await request.newContext({ baseURL: BASE_URL });
        const res = await ctx.get('/internal_api/arsip?fields[artikel]=email,session,token');

        // Spatie QueryBuilder menolak field di luar allowedFields (HTTP 4xx).
        // Apapun hasilnya, nilai/kolom sensitif tidak boleh sampai ke klien.
        expect(res.status()).toBeGreaterThanOrEqual(400);
        if (res.status() === 200) {
            const raw = await res.text();
            expect(raw).not.toContain('"email"');
            expect(raw).not.toContain('"session"');
            expect(raw).not.toContain(MARKER);
        }

        await ctx.dispose();
    });
});
