// Issue: #10715 — Tanggal perkawinan/perceraian muncul di anggota dengan status cerai mati
// https://github.com/OpenSID/OpenSID/issues/10715
import { test, expect } from '../../../utils/regressionTest';


test.describe('Bug/error: Tanggal Perkawinan/Perceraian pada hasil Unduhan Tombol Unduh dan Unduh F1.09 muncul di anggota yang status Perkawinan nya Cerai Mati #10715', () => {
  test('fix: perbaikan Tanggal Perkawinan/Perceraian pada hasil Unduhan Tombol Unduh dan Unduh F1.09', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10715',
    },
  }, async ({ page }) => {
    // KK id=50002 di-seed di seed.sql dengan skenario issue #10715 — 2 anggota:
    //   idx 0 (kepala, kk_level=1): CERAI MATI + tanggalperceraian='2026-01-02' → harus tampil '02-01-2026'
    //   idx 1 (anak,   kk_level=4): CERAI MATI + tanggalperceraian=NULL          → harus tampil '-'
    // Dua kk_level berbeda menjamin urutan deterministik (ORDER BY kk_level).
    await page.goto('keluarga/kartu_keluarga/50002');
    await page.waitForLoadState('networkidle');

    // Tabel kedua berisi kolom Status Perkawinan dan Tanggal Perkawinan/Perceraian
    const tabelPerkawinan = page.locator('table').filter({ hasText: 'Status Perkawinan' });
    await expect(tabelPerkawinan).toBeVisible();

    const baris = tabelPerkawinan.locator('tbody tr');

    // Baris 1: CERAI MATI dengan tanggal perceraian — harus tampil tanggal, bukan "-"
    const sel_baris1_tanggal = baris.nth(0).locator('td').nth(2);
    await expect(sel_baris1_tanggal).toContainText('02-01-2026');

    // Baris 2 (idx 1): CERAI MATI tanpa tanggal perceraian — harus tampil "-", bukan tanggal
    const sel_baris2_tanggal = baris.nth(1).locator('td').nth(2);
    await expect(sel_baris2_tanggal).toContainText('-');
    await expect(sel_baris2_tanggal).not.toContainText('2026');
  });
});
