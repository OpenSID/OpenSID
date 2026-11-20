// Issue: #10915 — Tidak ada notifikasi gagal pada simpan buku peraturan desa dengan format Word
// https://github.com/OpenSID/OpenSID/issues/10915
import { test, expect } from '../../../utils/regressionTest';
import path from 'path';


test.describe('Bug/error: Tidak Ada Notifikasi Gagal pada simpan Buku Peraturan desa dengan format Word #10915', () => {
  test('fix: perbaiakn tidak Ada Notifikasi Gagal pada simpan Buku Peraturan desa dengan format Word', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10915',
    },
  }, async ({ page }) => {
    // -----------------------------------------------------------------------
    // 1. Buka langsung form tambah Peraturan Desa (kat=3).
    //    Catatan: tombol "Tambah" pada daftar peraturan me-render URL
    //    `/form/3` (tanpa prefix `dokumen_sekretariat/`) yang menghasilkan
    //    404, jadi sesuai Pola #6 panduan kita navigasi langsung ke route
    //    yang benar dan hindari multi-step klik.
    // -----------------------------------------------------------------------
    await page.goto('dokumen_sekretariat/form/3');
    await page.waitForLoadState('domcontentloaded');
    await expect(page).toHaveURL(/.*form\/3/, { timeout: 15000 });

    // -----------------------------------------------------------------------
    // 3. Isi form dengan data valid
    //    Catatan: input "Judul Dokumen" hanya punya name="nama" (label-nya
    //    salah pakai for="nama" tanpa id), jadi pilih lewat atribut name.
    // -----------------------------------------------------------------------
    await page.locator('input[name="nama"]').fill('Test Peraturan Desa Otomatis');

    // Tipe Dokumen adalah <select>, bukan radio. Pilih "1" (File) agar
    // bagian unggah dokumen aktif dan field "satuan" menjadi required.
    await page.locator('#tipe').selectOption('1');

    // Jenis Peraturan adalah Select2 dengan tags:true — <select> asli
    // disembunyikan, jadi klik container Select2 lalu ketik tag dan Enter.
    await page.locator('select[name="attr[jenis_peraturan]"] + span.select2-container').click();
    await page.locator('.select2-search__field').fill('Peraturan Desa');
    await page.keyboard.press('Enter');

    await page.locator('input[name="attr[no_ditetapkan]"]').fill('001/2026');
    await page.locator('input[name="attr[tgl_ditetapkan]"]').fill('2026-01-01');
    await page.locator('input[name="attr[uraian]"]').fill('Uraian peraturan desa test otomatis');

    // -----------------------------------------------------------------------
    // 4. Upload file dengan format TIDAK diizinkan (.docx / Word)
    //    Format yang diizinkan: jpg, jpeg, png, pdf.
    //    Ada dua input bernama "satuan" (#file_path teks + #file file);
    //    set berkas ke #file. Event change-nya akan mengisi #file_path
    //    (lihat assets/js/script.js) sehingga POST 'satuan' tidak kosong.
    // -----------------------------------------------------------------------
    const invalidFilePath = path.resolve(__dirname, '../../../storage/fixtures/sample.docx');
    await page.locator('#file').setInputFiles(invalidFilePath);

    // -----------------------------------------------------------------------
    // 5. Submit form
    // -----------------------------------------------------------------------
    await page.getByRole('button', { name: /simpan/i }).click();

    // -----------------------------------------------------------------------
    // 6. Verifikasi: notifikasi error muncul setelah redirect balik ke /peraturan
    //    Pesan yang diharapkan:
    //    - "Data gagal disimpan"
    //    - "Jenis berkas yang Anda unggah tidak diperbolehkan."
    // -----------------------------------------------------------------------
    await expect(page).toHaveURL(/.*peraturan/, { timeout: 15000 });

    // Notifikasi gagal dirender sebagai blok notifikasi (heading "Gagal" + paragraf),
    // bukan `div.alert`. Verifikasi teks pesan yang muncul.
    await expect(page.getByText('Data gagal disimpan', { exact: false })).toBeVisible({ timeout: 15000 });
    await expect(
      page.getByText('Jenis berkas yang Anda unggah tidak diperbolehkan', { exact: false })
    ).toBeVisible({ timeout: 15000 });
  });
});
