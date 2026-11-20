// Issue: #7002 — KRITIS: internal_api/verifikasi-surat tanpa filter wajib membocorkan
// seluruh arsip surat desa + PDF (NIK, alamat, keperluan surat) tanpa auth
// https://github.com/OpenSID/premium/issues/7002
import { test, expect, request } from '../../../utils/regressionTest';
import { Laravel } from '../../../utils/laravel';

const BASE_URL = process.env.PLAYWRIGHT_BASE_URL || 'http://127.0.0.1:8000';

const LOG_SURAT_CETAK = 1;
const LOG_SURAT_KONSEP = 0;

async function suratId(status: number): Promise<number | null> {
    const rows = await Laravel.select(
        'SELECT id FROM log_surat WHERE status = ? AND deleted_at IS NULL ORDER BY id DESC LIMIT 1',
        [status],
    );
    return (rows?.[0] as { id?: number })?.id ?? null;
}

test.describe('API Verifikasi Surat - id wajib, hasil maksimal 1 record #7002', () => {
    test('Endpoint lama tanpa id sudah ditutup (404)', {
        annotation: { type: 'issue', description: 'https://github.com/OpenSID/premium/issues/7002' },
    }, async () => {
        const ctx = await request.newContext({ baseURL: BASE_URL });

        for (const url of [
            '/internal_api/verifikasi-surat',
            '/internal_api/verifikasi-surat?filter[id]=1',
            '/internal_api/verifikasi-surat-dinas',
            '/internal_api/verifikasi-surat-dinas?filter[id]=1',
        ]) {
            const res = await ctx.get(url);
            expect(res.status(), `${url} seharusnya tidak lagi melayani daftar surat`).toBe(404);
        }

        await ctx.dispose();
    });

    test('GET /internal_api/verifikasi-surat/{id} hanya mengembalikan 1 surat', {
        annotation: { type: 'issue', description: 'https://github.com/OpenSID/premium/issues/7002' },
    }, async () => {
        const id = await suratId(LOG_SURAT_CETAK);
        if (!id) {
            test.skip(true, 'Tidak ada surat berstatus cetak di database');
            return;
        }

        const ctx = await request.newContext({ baseURL: BASE_URL });
        const res = await ctx.get(`/internal_api/verifikasi-surat/${id}`);

        expect(res.status()).toBe(200);
        const body = await res.json();
        expect(Array.isArray(body.data)).toBeTruthy();
        expect(body.data.length).toBeLessThanOrEqual(1);
        // Datanya otomatis paling banyak 1 -> response tidak lagi memakai paginasi
        expect(body).not.toHaveProperty('meta');
        if (body.data.length === 1) {
            expect(String(body.data[0].id)).toBe(String(id));
        }

        await ctx.dispose();
    });

    test('Surat konsep/ditolak tidak bisa diverifikasi publik (404)', {
        annotation: { type: 'issue', description: 'https://github.com/OpenSID/premium/issues/7002' },
    }, async () => {
        const id = await suratId(LOG_SURAT_KONSEP);
        if (!id) {
            test.skip(true, 'Tidak ada surat berstatus konsep di database');
            return;
        }

        const ctx = await request.newContext({ baseURL: BASE_URL });
        const res = await ctx.get(`/internal_api/verifikasi-surat/${id}`);

        expect(res.status()).toBe(404);

        await ctx.dispose();
    });

    test('id tidak dikenal mengembalikan 404, bukan seluruh arsip', async () => {
        const ctx = await request.newContext({ baseURL: BASE_URL });
        const res = await ctx.get('/internal_api/verifikasi-surat/999999999');

        expect(res.status()).toBe(404);

        await ctx.dispose();
    });
});
