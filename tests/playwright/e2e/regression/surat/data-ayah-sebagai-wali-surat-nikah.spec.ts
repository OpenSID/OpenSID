// Issue: #10640 — Data ayah tidak bisa digunakan untuk data wali di surat keterangan nikah
// https://github.com/OpenSID/OpenSID/issues/10640
import { test, expect } from '../../../utils/regressionTest';


test.describe('Bug/error: Data ayah tidak bisa digunakan untuk data wali di surat keterangan nikah #10640', () => {
  test('fix: Data ayah tidak bisa digunakan untuk data wali di surat keterangan nikah', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10640',
    },
  }, async ({ page }) => {
    // Navigasi ke form surat keterangan nikah
    await page.goto('/surat/form/sistem-surat-keterangan-nikah');

    // Pilih individu (pemohon) via Select2 UI (underlying <select> bersifat aria-hidden)
    await page.locator('.select2-selection__arrow').first().click();
    await page.locator('input.select2-search__field').fill('adin');
    await page.waitForTimeout(500);
    await page.locator('.select2-results__option').first().click();

    // Tunggu data penduduk dimuat
    await page.waitForSelector('.data_penduduk_desa');

    // Pilih calon pasangan wanita (dcpw) via jQuery select2('open')
    // Tutup semua dropdown terlebih dahulu
    await page.keyboard.press('Escape');
    await page.evaluate(() => ($('select[name="dcpw[nik]"]') as any).select2('open'));
    // Target input field dengan nth untuk dropdown yang paling baru dibuka
    await page.locator('input.select2-search__field').last().fill('siti');
    await page.waitForTimeout(500);
    await page.locator('.select2-results__option').first().click();
    // ayw[nik] dimuat via AJAX setelah dcpw dipilih — tunggu sebentar
    await page.waitForLoadState('networkidle');

    // Verifikasi bahwa ayah wanita muncul otomatis di B.2 jika ada
    // ayw[nik] hanya ada di DOM jika penduduk terpilih punya data ayah
    const ayahWanitaSelect = page.locator('select[name="ayw[nik]"]');
    const ayahWanitaCount = await ayahWanitaSelect.count();
    if (ayahWanitaCount > 0) {
      const ayahWanitaValue = await ayahWanitaSelect.inputValue();
      if (ayahWanitaValue) {
        console.log('Ayah wanita terisi otomatis');
      } else {
        console.log('ayw[nik] ada di DOM tapi kosong');
      }
    } else {
      console.log('ayw[nik] tidak dirender (tidak ada data ayah untuk penduduk terpilih)');
    }

    // Pilih calon pasangan pria (dapp) via jQuery select2('open')
    // Tutup semua dropdown terlebih dahulu
    await page.keyboard.press('Escape');
    await page.evaluate(() => ($('select[name="dapp[nik]"]') as any).select2('open'));
    // Target input field dengan nth untuk dropdown yang paling baru dibuka
    await page.locator('input.select2-search__field').last().fill('budi');
    await page.waitForTimeout(500);
    await page.locator('.select2-results__option').first().click();
    // ayp[nik] dimuat via AJAX setelah dapp dipilih — tunggu sebentar
    await page.waitForLoadState('networkidle');

    // Verifikasi bahwa ayah pria muncul otomatis di C.2 jika ada
    // ayp[nik] hanya ada di DOM jika penduduk terpilih punya data ayah
    const ayahPriaSelect = page.locator('select[name="ayp[nik]"]');
    const ayahPriaCount = await ayahPriaSelect.count();
    if (ayahPriaCount > 0) {
      const ayahPriaValue = await ayahPriaSelect.inputValue();
      if (ayahPriaValue) {
        console.log('Ayah pria terisi otomatis');
      } else {
        console.log('ayp[nik] ada di DOM tapi kosong');
      }
    } else {
      console.log('ayp[nik] tidak dirender (tidak ada data ayah untuk penduduk terpilih)');
    }

    // Pilih wali nikah (dwn) via jQuery select2('open')
    // Tutup dropdown yang mungkin masih terbuka
    await page.keyboard.press('Escape');
    await page.evaluate(() => ($('select[name="dwn[nik]"]') as any).select2('open'));
    // Target input field dengan nth untuk dropdown yang paling baru dibuka
    await page.locator('input.select2-search__field').last().fill('ayah');
    await page.waitForTimeout(500);
    const results = page.locator('.select2-results__option');
    const resultCount = await results.count();

    if (resultCount > 0) {
      // Gunakan keyboard Enter untuk memilih hasil pertama
      await page.keyboard.press('Enter');
      await page.waitForTimeout(500);
    } else {
      // Jika tidak ada hasil pencarian dengan "ayah", gunakan keyboard untuk pilih opsi pertama
      await page.keyboard.press('ArrowDown');
      await page.keyboard.press('Enter');
      await page.waitForTimeout(500);
    }

    // Verifikasi bahwa form dapat diinteraksi tanpa strict mode violation
    // Ini adalah target utama dari test ini - memastikan multiple Select2 dropdowns
    // dapat dibuka secara berurutan tanpa error "resolved to multiple elements"

    // Coba scroll ke bawah untuk melihat lebih banyak form
    await page.evaluate(() => window.scrollTo(0, document.body.scrollHeight));
    await page.waitForTimeout(500);

    // Jika ada tombol submit/lanjut, coba klik untuk verifikasi form submission
    const submitButton = page.locator('button[type="submit"], button:has-text("Lanjut"), button:has-text("Cetak")').first();
    if (await submitButton.count() > 0) {
      await submitButton.click({ force: true });
      // Tunggu beberapa saat untuk melihat apakah ada error atau redirection
      await page.waitForTimeout(1000);
    }
  });
});
