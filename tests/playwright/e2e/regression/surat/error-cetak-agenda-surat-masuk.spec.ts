// Issue: #9343 — Terjadi error saat cetak agenda surat masuk
// https://github.com/OpenSID/OpenSID/issues/9343
import { test, expect } from '../../../utils/regressionTest';


test.describe('Terjadi error saat cetak agenda surat masuk (#9343)', () => {
  test('fix: perbaiki cetak unduh agenda surat masuk', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/9343',
    },
  }, async ({ page }) => {
    // 1. Akses halaman surat masuk
    await page.goto('surat_masuk');

    // 2. Klik tombol "Cetak"
    await page.locator('a.btn.dropdown-toggle').click();
    await page.getByRole('link', { name: 'Cetak' }).click();

    // 3. Pastikan modal cetak muncul
    await expect(page.locator('#modalBox')).toContainText('Tanggal Cetak', { timeout: 15000 });

    // 4. Tunggu jendela popup untuk proses cetak
    const page1Promise = page.waitForEvent('popup');

    // 5. Klik tombol "Cetak" pada form
    await page.locator('#form-cetak').getByText('Cetak', { exact: true }).click();

    // 6. Tangkap jendela popup yang muncul
    const page1 = await page1Promise;

    // 7. Verifikasi bahwa isi iframe pada popup berisi teks "AGENDA SURAT MASUK"
    await expect(
      page1.locator('iframe[name="print-frame"]').contentFrame().locator('span')
    ).toContainText('AGENDA SURAT MASUK');

    // 8. Tutup popup cetak
    await page1.getByRole('link', { name: 'Close' }).click();

    // 9. Buka lagi modal cetak untuk verifikasi tampilan ulang
    await page.locator('a.btn.dropdown-toggle').click();
    await page.getByRole('link', { name: 'Cetak' }).click();
    await expect(page.locator('#modalBox')).toContainText('Tanggal Cetak', { timeout: 15000 });
  });
});
