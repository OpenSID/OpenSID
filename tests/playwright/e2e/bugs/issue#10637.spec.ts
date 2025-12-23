import { test, expect } from '@playwright/test';

test.describe('Bug/error: QR CODE PADA SURAT DINAS TIDAK MUNCUL HANYA TERTULIS "[qr_code]" #10637', () => {
  test('fix: QR CODE PADA SURAT DINAS TIDAK MUNCUL HANYA TERTULIS', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10637',
    },
  }, async ({ page }) => {
        // Setup: Buat format surat dinas baru & aktifkan QR Code
        await page.goto('/surat_dinas/form');
        const letterName = `Test E2E QR Code ${new Date().getTime()}`;
        const nomorSurat = `140/BGS-TEST/${new Date().getTime()}`;
        const keterangan = 'Untuk keperluan testing Playwright Bug #10637.';

        await page.getByLabel('Nama Surat').fill(letterName);

        // Isi kolom wajib lainnya untuk membuat template surat baru
        await page.locator('#kode_surat').click();
        await page.locator('.select2-search__field').fill('400'); // Asumsi kode surat 400 ada
        await page.getByText('400 -', { exact: false }).click();

        await page.getByLabel('Template Isi Surat').fill('Ini adalah template untuk testing. Nomor Surat: [format_nomor_surat]');
        await page.getByLabel('Tampilkan QR Code').selectOption({ label: 'Ya' });

        await page.getByRole('button', { name: 'Simpan' }).click();
        await expect(page.locator('.alert-success')).toBeVisible();

        // 1. Pergi ke halaman Cetak Surat Dinas
        await page.goto('/surat_dinas_cetak');
        
        // 2. Cari surat yang baru dibuat, lalu klik "Buat Surat"
        await page.locator('tr', { hasText: letterName }).getByRole('link', { name: 'Buat Surat' }).click();

        // 3. Isi form surat
        await page.getByLabel('Nomor Surat').fill(nomorSurat);
        await page.getByLabel('Keterangan').fill(keterangan);
        
        // 4. Klik Pratinjau
        await page.getByRole('button', { name: 'Pratinjau' }).click();

        // 5. Pastikan QR code muncul di halaman pratinjau
        await expect(page.locator('img[alt="qrcode-surat"]')).toBeVisible();

        // 6. Klik Unduh dan tangkap ID arsip dari response header
        const [response] = await Promise.all([
            page.waitForResponse(resp => resp.url().includes('/surat_dinas_cetak/pdf') && resp.status() === 200),
            page.getByRole('button', { name: 'Unduh', exact: true }).click(),
        ]);

        const arsipId = response.headers()['id_arsip'];
        expect(arsipId).not.toBeNull();
        expect(arsipId).not.toBeUndefined();

        // 7. Uji alur verifikasi (simulasi scan QR code)
        await page.goto(`/c1/${arsipId}/surat_dinas`);

        // 8. Lakukan verifikasi di halaman final
        // Tunggu hingga URL berubah ke halaman verifikasi
        await page.waitForURL('**/verifikasi-surat-dinas/**');

        // Pastikan pesan error TIDAK muncul
        await expect(page.getByText('Surat tidak ditemukan dalam sistem')).not.toBeVisible();

        // Pastikan konten surat yang benar muncul
        await expect(page.getByRole('heading', { name: 'Verifikasi Surat' })).toBeVisible();
        await expect(page.getByText(nomorSurat, { exact: false })).toBeVisible();
        await expect(page.getByText(keterangan, { exact: false })).toBeVisible();
  });
});
