// Issue: #10637 — QR code pada surat dinas tidak muncul, hanya tertulis teks
// https://github.com/OpenSID/OpenSID/issues/10637
import { test, expect } from '../../../utils/regressionTest';


test.describe('Bug/error: QR CODE PADA SURAT DINAS TIDAK MUNCUL HANYA TERTULIS "[qr_code]" #10637', () => {
  test('fix: QR CODE PADA SURAT DINAS TIDAK MUNCUL HANYA TERTULIS', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10637',
    },
  }, async ({ page }) => {
        test.setTimeout(120000);

        // Buat format surat dinas baru dengan QR Code diaktifkan
        await page.goto('/surat_dinas/form');
        const letterName = `Test E2E QR Code ${Date.now()}`;

        await page.locator('input[name="nama"]').fill(letterName);

        // Pilih kode surat via Select2 — klik container yang di-render, bukan <select> tersembunyi
        await page.locator('#select2-kode_surat-container').click();
        await page.locator('.select2-search__field').last().fill('400');
        await page.locator('.select2-results__option', { hasText: '400' }).first().click();

        // Isi template di tab Template — sertakan [qr_code] agar substitusi benar-benar diuji
        await page.locator('a[href="#template-surat"]').click();
        await page.waitForSelector('iframe.tox-edit-area__iframe', { timeout: 15000 });
        await page.frameLocator('.tox-edit-area__iframe').locator('body').fill(
            'Template testing QR Code. [qr_code]'
        );

        // Aktifkan QR Code (radio button, bukan select)
        await page.locator('a[href="#pengaturan-umum"]').click();
        await page.locator('label.tipe:has(input[name="qr_code"][value="1"])').click();

        // Simpan surat baru
        await page.getByRole('button', { name: /Simpan dan Keluar/ }).click();
        await expect(page.locator('#notifikasi')).toBeVisible({ timeout: 15000 });

        // Pergi ke halaman Cetak Surat Dinas
        await page.goto('/surat_dinas_cetak');

        // Cari surat yang baru dibuat, lalu klik "Buat Surat"
        await page.locator('tr', { hasText: letterName }).getByRole('button', { name: 'Buat Surat' }).click();

        // Isi Nomor Surat — label tidak punya atribut for, pakai selector ID langsung
        await page.locator('#nomor').fill('001');

        // Klik Lanjut untuk memproses form dan membuka konsep/pratinjau
        await page.getByRole('button', { name: 'Lanjut' }).click();
        await page.waitForLoadState('networkidle');

        // Pada halaman konsep, klik Tinjau PDF dan verifikasi PDF berhasil dibuat
        // Bug #10637: jika [qr_code] tidak disubstitusi dengan benar, pdf() melempar exception → HTTP 500
        // dan SweetAlert menampilkan title error (statusText HTTP) bukan "Pratinjau"
        await page.waitForSelector('#preview-pdf', { timeout: 15000 });
        await page.locator('#preview-pdf').click();

        // SweetAlert pertama menampilkan loading, kemudian berubah ke "Pratinjau" setelah AJAX selesai
        await expect(page.locator('.swal2-title')).toContainText('Pratinjau', { timeout: 30000 });

        // Pastikan tidak ada icon error (yang muncul jika server mengembalikan 500)
        await expect(page.locator('.swal2-icon.swal2-error')).not.toBeVisible();
  });
});
