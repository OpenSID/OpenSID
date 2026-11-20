// Issue: #11217 — Popup DataTables Ajax Error muncul setelah memilih data penduduk meski data berhasil disimpan
// https://github.com/OpenSID/OpenSID/issues/11217
import { test, expect } from '../../../utils/regressionTest';


test.describe('Bug/error: Popup “DataTables Ajax Error” Muncul Setelah Memilih Data Penduduk Meski Data Berhasil Disimpan #11217', () => {
    test('fix: Popup “DataTables Ajax Error” Muncul Setelah Memilih Data Penduduk Meski Data Berhasil Disimpan #11217', {
        annotation: {
            type: 'issue',
            description: 'https://github.com/OpenSID/OpenSID/issues/11217',
        },
    }, async ({ page }) => {
        // Buka halaman rtm untuk men-setup session dan library jQuery
        await page.goto('rtm');

        // Lakukan AJAX POST ke rtm/datables_anggota/7201 via page.evaluate
        const response = await page.evaluate(async () => {
            return new Promise((resolve) => {
                $.ajax({
                    url: 'rtm/datables_anggota/7201',
                    method: 'POST',
                    success: function(data) {
                        resolve({ status: 200, data: data });
                    },
                    error: function(xhr) {
                        resolve({ status: xhr.status, responseText: xhr.responseText });
                    }
                });
            });
        }) as { status: number; data?: any; responseText?: string };

        // Pastikan response status adalah 200 (berhasil, bukan 404 atau 500)
        expect(response.status).toBe(200);
        expect(response.data).toBeDefined();
    });
});
