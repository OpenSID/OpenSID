// Issue: #10806 — Tombol tinjau surat masih muncul saat selesai cetak dan diklik error
// https://github.com/OpenSID/OpenSID/issues/10806
import { test, expect } from '../../../utils/regressionTest';


test.describe('Bug/error: Tombol Tinjau Surat masih Muncul saat Selesai Cetak dan diklik error #10806', () => {
  test('fix: perbaiki tombol Tinjau Surat masih Muncul saat Selesai Cetak dan diklik error', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10806',
    },
  }, async ({ page }) => {
    // PDF generation AJAX can be slow under session lock contention
    test.setTimeout(90000);
    await page.goto('/surat/form/sistem-surat-keterangan-penduduk');

    await page.locator('.select2-selection').first().click();
    await page.locator('.select2-search__field').fill('a');
    await expect(page.locator('.select2-results__option--highlighted')).toBeVisible({ timeout: 15000 });
    await page.locator('.select2-results__option--highlighted').click();
    await page.getByRole('textbox', { name: 'Masukkan Keperluan' }).fill('Perlu');
    await page.getByRole('button', { name: /Lanjut/ }).click();
    await expect(page.getByRole('heading', { name: 'Tinjau Surat' })).toBeVisible({ timeout: 15000 });

    await page.locator('#preview-pdf').click();
    // Wait for SweetAlert PDF preview to appear (replaces loading spinner after AJAX completes)
    const cetakBtn = page.locator('.swal2-footer').getByRole('button', { name: /Cetak/i });
    await expect(cetakBtn).toBeVisible({ timeout: 45000 });
    await cetakBtn.click();

    // Pastikan semua tombol/link yang dihilangkan setelah cetak tidak muncul lagi
    await expect(page.locator('#preview-pdf')).toHaveCount(0, { timeout: 15000 });
    await expect(page.locator('#konsep')).toHaveCount(0);
    await expect(page.locator('#back')).toHaveCount(0);
    await expect(page.locator('#ubah-surat')).toHaveCount(0);
    await expect(page.locator('#pengaturan')).toHaveCount(0);
  });
});
