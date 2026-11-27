// Issue: #10790 — Tampilan hasil cetak kolom pada surat pratinjau F-1.06
// https://github.com/OpenSID/OpenSID/issues/10790
import { test, expect } from '../../../utils/regressionTest';


test.describe('Bug/error: Tampilan Hasil Cetak Kolom "Pendidikan dan Pekerjaan" Lampiran F-1.06 #10790', () => {
  test('fix: perbaikan Tampilan Hasil Cetak Lampiran F-1.06', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10790',
    },
  }, async ({ page }) => {
    // Navigasi langsung ke form surat Keterangan Beda Identitas (url_surat F-1.06)
    await page.goto('surat/form/sistem-surat-keterangan-beda-identitas');
    await page.waitForLoadState('networkidle');

    // Pilih penduduk AHLUL di bagian Keterangan Pemohon
    await page.getByText('-- Cari NIK / Tag ID Card /').click();
    await page.locator('input[type="search"]').fill('ahlul');
    await page.waitForTimeout(500);
    await page.getByText('5201142005716996').click();
    await page.waitForTimeout(300);

    // Isi field wajib di bagian Identitas Kedua
    await page.getByPlaceholder('Nama Kartu Identitas').fill('KTP');
    await page.getByPlaceholder('Nomor Identitas').fill('9876543210123456');
    await page.getByPlaceholder('Nama').last().fill('AHLUL BERBEDA');
    await page.getByPlaceholder('Tempat Lahir').fill('Mangsit');
    await page.locator('input[name="tanggal_lahir"]').fill('01-01-1970');
    await page.locator('select[name="jenis_kelamin"]').selectOption({ index: 1 });
    await page.locator('select[name="agama"]').selectOption({ index: 1 });
    await page.locator('select[name="pekerjaan"]').selectOption({ index: 1 });
    await page.getByPlaceholder('Alamat').fill('Dusun Mangsit RT 01');
    await page.getByPlaceholder('Keterangan').fill('Perbedaan nama pada dokumen');
    await page.getByPlaceholder('Perbedaan').fill('Nama');

    // Pilih pamong penandatangan (opsi pertama yang tersedia)
    // pamong_id adalah Select2 (aria-hidden) — force:true untuk bypass visibility check
    await page.locator('select[name="pamong_id"]').selectOption({ index: 1 }, { force: true });

    // Submit form ke pratinjau
    await page.getByRole('button', { name: /Lanjut/ }).click();
    await page.waitForLoadState('networkidle');

    // Verifikasi halaman konsep/pratinjau berhasil dimuat
    await expect(page.locator('#preview-pdf')).toBeVisible({ timeout: 15000 });

    // Klik tab lampiran F-1.06
    await page.locator('ul.nav-tabs a:has-text("F-1.06")').click();
    await page.waitForTimeout(2000); // Tunggu TinyMCE init pada tab lampiran

    // Verifikasi editor lampiran F-1.06 memuat kolom Pendidikan dan Pekerjaan
    const lampiranEditor = page.locator('iframe[title="Rich Text Area"]').nth(1).contentFrame();
    await expect(lampiranEditor.getByLabel('Rich Text Area')).toContainText('Pendidikan', { timeout: 10000 });
    await expect(lampiranEditor.getByLabel('Rich Text Area')).toContainText('Pekerjaan', { timeout: 10000 });
  });
});
