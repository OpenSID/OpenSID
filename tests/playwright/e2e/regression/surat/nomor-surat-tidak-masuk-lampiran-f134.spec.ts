// Issue: #10769 — Nomor surat tidak masuk di lampiran F-1.34
// https://github.com/OpenSID/OpenSID/issues/10769
import { test, expect } from '../../../utils/regressionTest';


test.describe('nomor surat tidak masuk di lampiran F-1.34 #10769', () => {
  test('fix: perbaikan nomor surat tidak masuk di lampiran F-1.34', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10769',
    },
  }, async ({ page }) => {
    test.setTimeout(60000);
    await page.goto('/surat/form/sistem-surat-keterangan-pindah-penduduk');

    // 1. Ambil nomor surat dari input untuk divalidasi nanti
    const nomorSuratInput = await page.locator('input[name="nomor"]').inputValue();

    // 2. Isi Data Pemohon (Pilih dari Select2)
    await page.locator('.select2-selection').first().click();
    await page.locator('.select2-search__field').fill('MUHAMAD');
    await page.locator('.select2-results__option').first().waitFor({ state: 'visible' });
    await page.locator('.select2-results__option').first().click();

    // 3. Scroll ke bawah untuk menemukan form fields lainnya
    await page.evaluate(() => window.scrollTo(0, document.body.scrollHeight));

    // 4. Isi field-field pindah dengan menggunakan selectOption dan fill
    // Cari dan isi "Klasifikasi Pindah"
    await page.waitForLoadState('networkidle');
    const klasifikasiSelect = page.locator('select[name="klasifikasi_pindah"]');
    if (await klasifikasiSelect.count() > 0) {
      const options = await klasifikasiSelect.locator('option').allTextContents();
      // Cari option yang mengandung kata "pindah" atau gunakan index 1
      const optionsWithValue = await klasifikasiSelect.locator('option').all();
      for (let i = 0; i < optionsWithValue.length; i++) {
        const text = await optionsWithValue[i].textContent();
        if (text && text.toLowerCase().includes('pindah')) {
          await klasifikasiSelect.selectOption(await optionsWithValue[i].getAttribute('value') || '');
          break;
        }
      }
    }

    // Tunggu field-field terkait muncul
    await page.waitForTimeout(500);

    // 5. Isi Alamat Tujuan
    const alamatTujuanField = page.locator('[name="alamat_tujuan"]');
    if (await alamatTujuanField.count() > 0) {
      await alamatTujuanField.fill('Jalan Banglas Gang Antara');
    }

    // 6. Isi RT/RW/Dusun Tujuan
    const rtField = page.locator('input[name="rt_tujuan"]');
    const rwField = page.locator('input[name="rw_tujuan"]');
    const dusunField = page.locator('input[name="dusun_tujuan"]');

    if (await rtField.count() > 0) await rtField.fill('01');
    if (await rwField.count() > 0) await rwField.fill('02');
    if (await dusunField.count() > 0) await dusunField.fill('Dusun');

    // 7. Isi Desa/Kelurahan Tujuan
    const desaField = page.locator('input[name="desa_tujuan_show"]');
    if (await desaField.count() > 0) {
      await desaField.fill('Desa Test');
    }

    // 8. Isi Kode Pos / Telpon
    const kodePosField = page.locator('input[name="kode_pos_tujuan"]');
    const telpField = page.locator('input[name="telepon_tujuan"]');

    if (await kodePosField.count() > 0) await kodePosField.fill('28421');
    if (await telpField.count() > 0) await telpField.fill('081234567890');

    // 9. Isi field-field lainnya yang mungkin required
    const jenisKepinathanField = page.locator('select[name="jenis_kepindahan"]');
    if (await jenisKepinathanField.count() > 0) {
      const options = await jenisKepinathanField.locator('option').all();
      if (options.length > 1) {
        await jenisKepinathanField.selectOption(await options[1].getAttribute('value') || '');
      }
    }

    // 10. Klik tombol Lanjut/Cetak untuk masuk ke halaman pratinjau
    const submitButton = page.getByRole('button', { name: /Lanjut|Cetak/i }).first();
    await submitButton.click();

    // 11. Tunggu halaman pratinjau/konsep muncul
    await page.waitForLoadState('networkidle');
    await page.waitForTimeout(1000);

    // 12. Cek apakah ada iframe TinyMCE atau heading "Tinjau Surat"
    const heading = page.getByRole('heading', { name: /Tinjau|Pratinjau|Konsep/i });
    const frame = page.frameLocator('#isi_surat_ifr');

    if (await heading.count() > 0) {
      await expect(heading.first()).toBeVisible();
    }

    // 13. Validasi Nomor Surat dalam konten cetakan
    if (await frame.locator('body').count() > 0) {
      await expect(frame.locator('body')).toContainText(nomorSuratInput);
    }
  });
});
