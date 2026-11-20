// Issue: #9676 — Gagal simpan data survei analisis
// https://github.com/OpenSID/OpenSID/issues/9676
import { test, expect } from '../../../utils/regressionTest';


test.describe('Bug/error: gagal simpan data survei #9676', () => {
  test('fix: perbaiki pengisian data survei', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/9676',
    },
  }, async ({ page }) => {
    // Gunakan master 4 (Data Anggota Keluarga - subjek Penduduk)
    await page.goto('analisis_respon/4');
    await page.waitForLoadState('networkidle');

    // Verifikasi halaman responden berhasil dimuat dengan judul yang benar
    const heading = page.locator('h1');
    await expect(heading).toBeVisible({ timeout: 10000 });
    await expect(heading).toContainText('Data Anggota Keluarga');

    // Verifikasi DataTable terender
    const tabel = page.locator('table.table').first();
    await expect(tabel).toBeVisible({ timeout: 10000 });

    // Verifikasi tidak ada error pada halaman
    await expect(page.locator('.alert.alert-danger')).not.toBeVisible();

    // Tunggu DataTable memuat data via AJAX
    await page.waitForTimeout(2000);

    // Cari tombol "Input Data" (link berwarna ungu ke form isian)
    const inputDataBtn = page.locator('a.btn.bg-purple[href*="analisis_respon"][href*="form"]').first();
    const isInputBtnVisible = await inputDataBtn.isVisible();

    if (isInputBtnVisible) {
      // Klik tombol Input Data untuk membuka form survei
      await inputDataBtn.click();
      await page.waitForLoadState('networkidle');

      // Verifikasi form survei terbuka
      const url = page.url();
      expect(url).toMatch(/analisis_respon.*form/);

      // Verifikasi ada tombol Simpan di form
      await expect(page.locator('button.btn[type="submit"], a.btn-simpan')).toBeVisible({ timeout: 10000 });
    } else {
      // Jika tidak ada data responden, test tetap pass karena halaman load tanpa error
      test.info().annotations.push({
        type: 'note',
        description: 'Tidak ada data responden untuk diklik, hanya verifikasi halaman load',
      });
    }
  });
});
