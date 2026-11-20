// Issue: #9411 — Cetak/unduh data calon pemilih
// https://github.com/OpenSID/OpenSID/issues/9411
import { test, expect } from '../../../utils/regressionTest';

test.describe('Cetak/Unduh Data Calon Pemilih #9411', () => {
  test('fix: perbaiki cetak/unduh data calon pemilih', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/9411',
    },
  }, async ({ page }) => {
    // 1. Buka halaman DPT dan tunggu DataTable pertama kali muat
    await page.goto('dpt');
    await page.waitForLoadState('domcontentloaded');
    await page.waitForSelector('table#tabeldata tbody tr', { timeout: 15000 });

    // 2. Filter Jenis Kelamin = Laki-laki via Select2
    await page.locator('span.select2-container').filter({ hasText: 'Pilih Jenis Kelamin' }).click();
    await page.getByRole('treeitem', { name: 'Laki-laki' }).click();

    // 3. Buka modal Pencarian Spesifik
    // Elemen ini <a> tanpa href → ARIA role-nya "generic", bukan "link"
    await page.locator('a[data-target="#modal-search-form"]').click();
    await expect(page.locator('#modal-search-form')).toBeVisible({ timeout: 15000 });

    // 4. Pilih Status Perkawinan = KAWIN via Select2 di modal
    await page.locator('#select2-status_kawin-container').click();
    await page.getByRole('treeitem', { name: 'KAWIN', exact: true }).click();

    // 5. Simpan filter dan tunggu DataTable reload dengan filter baru
    await page.locator('#btnSearchAdvance').click();
    await expect(page.locator('#tabeldata_info')).toContainText('Menampilkan 1 sampai 50', { timeout: 15000 });

    // 6. Buka dropdown Cetak/Unduh
    await page.locator('.btn-group-vertical a.bg-purple').click();

    // 7. Klik "Cetak" di dropdown — membuka #modalBox via AJAX
    await page.locator('.btn-group-vertical .dropdown-menu').getByRole('link', { name: /cetak/i }).click();

    // 8. Tunggu form cetak muncul di dalam modal
    await expect(page.locator('#form-cetak')).toBeVisible({ timeout: 15000 });

    // 9. Submit form-cetak — membuka halaman cetak di popup (target="_blank")
    const popupPromise = page.waitForEvent('popup');
    await page.locator('#form-cetak button[type="submit"]').click();
    const popup = await popupPromise;

    // 10. Verifikasi halaman cetak memuat data yang benar
    await popup.waitForLoadState('domcontentloaded');
    await expect(popup.getByRole('heading')).toContainText('DAFTAR CALON PEMILIH UNTUK TANGGAL PEMILIHAN', { timeout: 15000 });
    const rows = popup.locator('tbody tr');
    const rowCount = await rows.count();
    const lastRow = rows.nth(rowCount - 1);
    await expect(lastRow).toContainText('50');
  });
});
