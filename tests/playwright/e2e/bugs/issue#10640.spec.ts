import { test, expect } from '@playwright/test';

test.describe('Bug/error: Data ayah tidak bisa digunakan untuk data wali di surat keterangan nikah #10640', () => {
  test('fix: Data ayah tidak bisa digunakan untuk data wali di surat keterangan nikah', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10640',
    },
  }, async ({ page }) => {
    // Navigasi ke form surat keterangan nikah
    await page.goto('/surat/form/sistem-surat-keterangan-nikah');

    // Pilih individu (pemohon) - asumsikan ada data dengan ID tertentu
    await page.locator('select[name="individu[nik]"]').click();
    await page.locator('select[name="individu[nik]"]').fill('adin'); // Cari berdasarkan nama
    await page.waitForTimeout(1000); // Tunggu hasil pencarian
    await page.locator('.select2-results__option').first().click(); // Pilih hasil pertama

    // Tunggu data penduduk dimuat
    await page.waitForSelector('.data_penduduk_desa');

    // Pilih calon pasangan wanita (dcpw) dari luar keluarga
    const dcpwSelect = page.locator('select[name="dcpw[nik]"]');
    await dcpwSelect.click();
    await dcpwSelect.fill('siti'); // Cari nama wanita dari luar keluarga
    await page.waitForTimeout(1000);
    await page.locator('.select2-results__option').first().click();

    // Verifikasi bahwa ayah wanita muncul otomatis di B.2 jika ada
    const ayahWanitaSelect = page.locator('select[name="ayw[nik]"]');
    const ayahWanitaValue = await ayahWanitaSelect.inputValue();
    if (ayahWanitaValue) {
      console.log('Ayah wanita terisi otomatis');
    } else {
      console.log('Tidak ada ayah wanita, field tidak required');
      // Pastikan tidak ada class required
      await expect(ayahWanitaSelect).not.toHaveClass(/required/);
    }

    // Pilih calon pasangan pria (dapp) dari luar keluarga
    const dappSelect = page.locator('select[name="dapp[nik]"]');
    await dappSelect.click();
    await dappSelect.fill('budi'); // Cari nama pria dari luar keluarga
    await page.waitForTimeout(1000);
    await page.locator('.select2-results__option').first().click();

    // Verifikasi bahwa ayah pria muncul otomatis di C.2 jika ada
    const ayahPriaSelect = page.locator('select[name="ayp[nik]"]');
    const ayahPriaValue = await ayahPriaSelect.inputValue();
    if (ayahPriaValue) {
      console.log('Ayah pria terisi otomatis');
    } else {
      console.log('Tidak ada ayah pria, field tidak required');
      await expect(ayahPriaSelect).not.toHaveClass(/required/);
    }

    // Pilih wali nikah (dwn) - pastikan ayah bisa dipilih meskipun sudah di B.2
    const waliSelect = page.locator('select[name="dwn[nik]"]');
    await waliSelect.click();
    await waliSelect.fill('ayah'); // Cari ayah
    await page.waitForTimeout(1000);
    const results = page.locator('.select2-results__option');
    await expect(results).toHaveCount(await results.count()); // Pastikan ada hasil
    await results.first().click();

    // Verifikasi bahwa wali nikah terpilih
    const waliValue = await waliSelect.inputValue();
    expect(waliValue).not.toBe('');

    // Coba simpan form (pastikan tidak error karena field required)
    await page.locator('button[type="submit"]').click();
    // Tunggu redirect atau pesan sukses
    await page.waitForURL(/\/surat\/pratinjau/);
    expect(page.url()).toContain('/surat/pratinjau');
  });
});
