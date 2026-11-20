// Issue: #11108 — Surat keterangan harga tanah sistem ketika disalin dan disimpan muncul tanda ] di tabel jumlah
// https://github.com/OpenSID/OpenSID/issues/11108
import { test, expect } from '../../../utils/regressionTest';


test.describe('Bug/error: surat keterangan harga tanah sistem, ketika disalin dan disimpan, muncul tanda ] di tabel jumlah #11108', () => {
    test('fix: surat keterangan harga tanah sistem, ketika disalin dan disimpan, muncul tanda ] di tabel jumlah', {
        annotation: {
            type: 'issue',
            description: 'https://github.com/OpenSID/OpenSID/issues/11108',
        },
    }, async ({ page }) => {
        // 1. Buka halaman salin surat Keterangan Harga Tanah
        // salin/ melakukan redirect ke form/?salin=1, sehingga URL berubah ke form/
        await page.goto('/surat_master/salin/1303');
        await expect(page).toHaveURL(/surat_master\/form\/\d+/);

        // 2. Isi nama layanan surat (dikosongkan otomatis saat salin)
        const inputNama = page.locator('input[name="nama"]');
        await expect(inputNama).toBeVisible();
        await inputNama.fill(`Harga Tanah Test ${Date.now()}`);

        // 3. Simpan Sementara
        await page.locator('#simpan-sementara').first().click();

        // 4. Tunggu SweetAlert sukses, lalu klik OK
        await expect(page.locator('.swal2-popup')).toBeVisible({ timeout: 10_000 });
        await expect(page.locator('.swal2-title')).toContainText('Berhasil');
        await page.locator('.swal2-confirm').click();

        // 5. Klik tombol Tinjau PDF — sebelum perbaikan muncul error karena karakter ]
        const tinjauBtn = page.getByRole('button', { name: /Tinjau PDF/i });
        await expect(tinjauBtn).toBeVisible({ timeout: 5_000 });
        await tinjauBtn.click();

        // 6. Pastikan pratinjau PDF berhasil dimuat tanpa error (tidak ada status 404/500)
        await expect(page.getByRole('heading', { name: 'Pratinjau' })).toBeVisible({ timeout: 15_000 });
    });
});
