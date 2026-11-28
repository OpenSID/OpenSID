// Issue: #10753 — QR code surat dinas pada halaman cetak
// https://github.com/OpenSID/OpenSID/issues/10753
import { test, expect } from '../../../utils/regressionTest';


/**
 * Test untuk Issue #10753: QR Code tidak muncul pada surat dinas
 *
 * Bug: QR Code pada surat dinas tidak muncul saat tinjau maupun hasil cetak
 * Test ini memverifikasi bahwa placeholder [qr_code] tetap ada di konten konsep
 * (pratinjau server-side tidak mensubstitusi [qr_code]; substitusi terjadi saat PDF).
 */

test.describe('Issue #10753 - QR Code Surat Dinas', () => {
  test('Placeholder [qr_code] harus tetap ada di konten editor surat', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10753',
    },
  }, async ({ page }) => {
    // Navigasi ke halaman cetak surat dinas
    await page.goto('/surat_dinas_cetak');

    // Tunggu tabel surat muncul
    await page.waitForSelector('table tbody tr');

    // Klik tombol "Buat Surat" pada surat "Pernyataan" (id=1, satu-satunya surat dengan [qr_code] di template)
    await page.locator('tr', { hasText: 'Pernyataan' }).getByRole('button', { name: 'Buat Surat' }).click();
    await page.waitForLoadState('networkidle');

    // Isi form kode isian yang required (sistem-surat-pernyataan)
    await page.getByRole('textbox', { name: 'Isi Nama', exact: true }).fill('Test User');
    await page.getByRole('textbox', { name: 'Isi Jabatan' }).fill('Perangkat Desa');
    await page.getByRole('textbox', { name: 'Isi Alamat' }).fill('Jl. Test No. 1');
    await page.getByRole('textbox', { name: 'Isi Nama Kepala Inspektorat' }).fill('Inspektur');
    await page.getByRole('textbox', { name: 'Isi Kepala Dinas PMD' }).fill('Kepala PMD');

    // Submit form untuk ke halaman konsep
    await page.getByRole('button', { name: ' Lanjut' }).click();
    await page.waitForLoadState('networkidle');

    // Tunggu TinyMCE editor ready pada halaman konsep
    await page.waitForSelector('iframe.tox-edit-area__iframe', { timeout: 15000 });

    // Akses iframe TinyMCE untuk melihat konten
    const editorFrame = page.frameLocator('iframe.tox-edit-area__iframe');

    // Assert: [qr_code] placeholder tetap ada di editor (substitusi terjadi saat pdf(), bukan pratinjau())
    await expect(editorFrame.locator('body')).toContainText('[qr_code]', { timeout: 10000 });
  });
});
