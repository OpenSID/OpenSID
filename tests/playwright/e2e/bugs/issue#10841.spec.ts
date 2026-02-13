import { test, expect } from '@playwright/test';
import path from 'path';

test.describe('Bug/error: Critical Race Condition pada Layanan Mandiri #10841', () => {
  test('fix: cegah submit berulang dengan script otomasi klik', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10841'
    }
  }, async ({ page }) => {

    // 1. Masuk ke layanan mandiri
    await page.goto('layanan-mandiri/masuk');

    // 2. Login dengan NIK dan PIN pengguna mandiri
    await page.getByRole('textbox', { name: 'NIK' }).fill('5108012209960003');
    await page.getByRole('textbox', { name: 'PIN' }).fill('475259');
    await page.getByRole('button', { name: 'MASUK', exact: true }).click();

    // 3. Navigasi ke halaman buat surat
    await page.goto('layanan-mandiri/permohonan-surat');

    // 4. Klik tombol Buat Surat
    await page.getByRole('link', { name: /Buat Surat/i }).click();
    await page.waitForLoadState('networkidle');

    // 5. Isi form permohonan surat
    // Pilih jenis surat dari dropdown
    const suratDropdown = page.locator('select[name*="surat"], select[name*="url_surat"]');
    await suratDropdown.selectOption({ index: 1 }); // Pilih option kedua (skip placeholder)

    // 6. Isi keterangan tambahan
    await page.getByPlaceholder(/keterangan|Keterangan/i).fill('Test permohonan surat - Issue #10841');

    // 7. Isi nomor HP aktif
    await page.getByPlaceholder(/No\.\s*HP|HP aktif|nomor hp/i).fill('081234567890');

    // 8. Klik tombol "Isi Form"
    await page.getByRole('button', { name: /Isi Form|Lanjutkan/i }).click();

    // 9. Klik tombol "Kirim" dan assert disabled + text
    const submitButton = page.locator('button[type="submit"].btn-success.pull-right');

    await submitButton.click();

    // 10. Verifikasi berhasil redirect
    expect(page.url()).toContain('layanan-mandiri/permohonan-surat');

    // 11. Verifikasi tidak ada error
    const errorMessages = page.locator('text=/TypeError|error/i');
    await expect(errorMessages).not.toBeVisible();

  });
});
