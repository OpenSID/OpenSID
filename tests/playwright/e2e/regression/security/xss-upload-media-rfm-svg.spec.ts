// Issue: #11136 — Celah keamanan XSS pada upload media di RFM dan eksekusi payload di image SVG
// https://github.com/OpenSID/OpenSID/issues/11136
import { test, expect } from '../../../utils/regressionTest';


test.describe('Bug/error: Celah Keamanan XSS pada upload media di RFM dan eksekusi payload di image .svg #11136', () => {
    test('fix: Celah Keamanan XSS pada upload media di RFM dan eksekusi payload di image .svg #11136', {
        annotation: {
            type: 'issue',
            description: 'https://github.com/OpenSID/OpenSID/issues/11136',
        },
    }, async ({ page }) => {
        // 1. Kunjungi rfm/dialog.php untuk inisialisasi sesi RFM (memicu $_SESSION['RF']['verify'])
        await page.goto('rfm/dialog.php');

        // 2. Siapkan file SVG yang sudah disisipi payload XSS (Script)
        const payloadXSS = '<svg xmlns="http://www.w3.org/2000/svg"><script>alert("XSS payload")</script><text>Hacked</text></svg>';

        // 3. Lakukan HTTP POST langsung ke handler upload (gunakan page.request agar cookie sesi dibagikan)
        const response = await page.request.post('rfm/upload.php', {
            multipart: {
                fldr: '',
                files: {
                    name: 'payload.svg',
                    mimeType: 'image/svg+xml',
                    buffer: Buffer.from(payloadXSS)
                }
            }
        });

        // 4. Pastikan response sukses dari sisi HTTP, tapi gagal dari sisi logika sistem
        expect(response.ok()).toBeTruthy();
        
        const body = await response.json();

        // 5. Verifikasi sistem mendeteksi konten berbahaya dan menggagalkan unggahan
        expect(body.files).toBeDefined();
        expect(body.files[0].error).toBeDefined();
        expect(body.files[0].error).toContain('File is dangerous');
    });
});