// Issue: #10923 — Nama penanda tangan tidak muncul pada cetak dan unduh data rekapitulasi penduduk
// https://github.com/OpenSID/OpenSID/issues/10923
import { test, expect } from '../../../utils/regressionTest';


test.describe('Bug/error: Nama penanda tangan tidak muncul pada cetak dan unduh data rekapitulasi penduduk #10923', () => {
  test('fix: perbaikan Nama penanda tangan tidak muncul pada cetak dan unduh data rekapitulasi penduduk', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10923',
    },
  }, async ({ page }) => {
    await page.goto('bumindes_penduduk_rekapitulasi');

    // Pastikan halaman berhasil dimuat tanpa error
    await expect(page).not.toHaveURL(/error|404|500/);
    await expect(page.locator('body')).not.toContainText('500');

    // Buka dialog cetak — penanda tangan dipilih di dialog, bukan di halaman utama
    await page.locator('a.btn.dropdown-toggle').click();
    await page.getByRole('link', { name: 'Cetak' }).click();

    // Tunggu modal muncul
    await expect(page.locator('#modalBox')).toBeVisible({ timeout: 15000 });

    // Verifikasi form dialog cetak memiliki input tgl_cetak
    await expect(page.locator('#tgl_1')).toBeVisible();

    // Klik submit dan tunggu popup cetak
    const submitButton = page.locator('#modalBox button[type="submit"]').first();
    await expect(submitButton).toBeVisible();
    const page2Promise = page.waitForEvent('popup');
    await submitButton.click();
    const page2 = await page2Promise;

    await page2.waitForLoadState('networkidle');
    const pageContent = await page2.content();

    // Verifikasi tidak ada error 500
    expect(pageContent).not.toContain('HTTP ERROR 500');
    expect(pageContent).not.toContain('Whoops');

    // Verifikasi iframe print-frame muncul (menandakan halaman cetak berhasil)
    const printFrame = page2.locator('iframe[name="print-frame"]').first();
    await expect(printFrame).toBeVisible({ timeout: 15000 });

    // Verifikasi konten iframe berisi data (nama pamong ada di blok TTD)
    const frameContent = printFrame.contentFrame();
    const cetakBody = await frameContent.locator('body').textContent({ timeout: 10000 });
    expect(cetakBody).toBeTruthy();
    expect(cetakBody!.length).toBeGreaterThan(100);
  });
});
