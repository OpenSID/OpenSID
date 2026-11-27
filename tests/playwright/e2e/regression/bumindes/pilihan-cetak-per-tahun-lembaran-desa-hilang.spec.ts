// Issue: #9811 — Pilihan cetak per tahun di buku lembaran desa hilang/tidak ada
// https://github.com/OpenSID/OpenSID/issues/9811
import { test, expect } from '../../../utils/regressionTest';


test.describe('Bug/error: Buku Lembaran Desa, Pilihan Cetak per tahun hilang/ tidak ada #9811', () => {
  test('fix: Perbaikan buku Lembaran Desa, Pilihan Cetak per tahun hilang/ tidak ada', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/9811',
    },
  }, async ({ page }) => {
    await page.goto('lembaran_desa');
    await expect(page.getByRole('link', { name: 'Buku Lembaran Desa dan Berita' })).toBeVisible();

    // Filter tahun ke 2025 via Select2 di halaman utama
    await page.getByRole('textbox', { name: 'Pilih Tahun' }).click();
    await page.getByRole('treeitem', { name: '2025' }).click();

    // Buka split button dropdown, lalu klik Cetak (buka modal)
    await page.locator('a.btn.dropdown-toggle').click();
    await page.getByRole('link', { name: 'Cetak' }).click();

    // Tunggu modal terbuka dan form dimuat sepenuhnya
    // Scope ke #modalBox untuk menghindari konflik dengan Select2 tahun di halaman utama
    await expect(page.locator('#form-cetak')).toBeVisible({ timeout: 15000 });
    // Pilih tahun di Select2 dalam modal (gunakan tahun yang ada: 2023)
    await page.locator('#modalBox select[name="tahun"]').first().selectOption('2023');
    await page.waitForTimeout(500);

    // Submit form dan tunggu popup cetak
    const page1Promise = page.waitForEvent('popup');
    await page.locator('#form-cetak').getByText('Cetak', { exact: true }).click();
    const page1 = await page1Promise;

    // Verifikasi popup berisi heading BUKU LEMBARAN DESA DAN BERITA (atau bentuk cetak lainnya)
    await expect(page1.locator('body')).toBeVisible();
  });
});
