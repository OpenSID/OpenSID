import { test, expect } from '@playwright/test';
import path from 'path';
import { Laravel } from '../../utils/laravel';

test.describe('Bug/error: Error saat klik Laporan PDF pada Buku Rekapitulasi Jumlah Penduduk #10886', () => {
  test('fix: perbaikan Laporan PDF pada Buku Rekapitulasi Jumlah Penduduk', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10886'
    }
  }, async ({ page }) => {
      
    // 1. Navigasi ke halaman rekapitulasi
    await page.goto('bumindes_penduduk_rekapitulasi');
    await expect(page).toHaveURL(/bumindes_penduduk_rekapitulasi/);

    // 2. Pastikan halaman berhasil dimuat (tidak error 500)
    await expect(page.locator('body')).not.toContainText('500');
    await expect(page.locator('body')).not.toContainText('Whoops');

    // 3. Klik tombol Laporan PDF
    const pdfButton = page.getByRole('link', { name: /laporan pdf/i })
      .or(page.locator('a[href*="laporan_pdf"], button:has-text("PDF")').first());
    await expect(pdfButton).toBeVisible();
    await pdfButton.click();

    // 4. Tunggu response — harusnya download PDF, bukan error
    const [download] = await Promise.all([
      page.waitForEvent('download', { timeout: 15_000 }),
    ]);

    // 5. Validasi file PDF berhasil diunduh
    const fileName = download.suggestedFilename();
    expect(fileName).toMatch(/rekap_jumlah_penduduk_.*\.pdf$/i);

    // 6. Pastikan judul PDF bukan hanya "pdf" (fix dari $data['file'])
    expect(fileName).not.toBe('pdf.pdf');
    expect(fileName.length).toBeGreaterThan('pdf.pdf'.length);
  });
});
