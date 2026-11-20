// Issue: #10617 — QR code pada surat dinas tidak muncul setelah cetak
// https://github.com/OpenSID/OpenSID/issues/10617
//
// Flow:
//   1. Aktifkan qr_code pada surat "Perintah Perjalanan Dinas" (id=268, jenis=3) via surat_master
//   2. Buka halaman Cetak Surat (/surat), cari surat yang sama
//   3. Buat surat untuk penduduk seed, isi kode_isian required
//   4. Klik Lanjut → konsep — verifikasi TinyMCE muncul (tidak crash saat QR diaktifkan)
import { test, expect } from '../../../utils/regressionTest';


// ID surat "Perintah Perjalanan Dinas" di tweb_surat_format (jenis=3, kunci=0)
const SURAT_ID = 268;
// NIK penduduk seed (RAHAMIN, cluster KEDEMANGAN MAKMUR)
const PENDUDUK_NIK = '1505022308730003';

test.describe('Bug/error: QrCode pada surat dinas tidak muncul setelah cetak #10617', () => {
  test('fix: perbaiki QrCode pada surat dinas tidak muncul setelah cetak', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10617',
    },
  }, async ({ page }) => {
    test.setTimeout(90000);

    // ── BAGIAN 1: Verifikasi qr_code aktif di surat_master ──────────────────
    // qr_code = 1 sudah diset via seed.sql UPDATE; tidak perlu menyimpan via form
    // (form surat_master mengalami masalah TinyMCE saat auto-submit via Playwright)

    await page.goto(`/surat_master/form/${SURAT_ID}`);
    await page.waitForLoadState('networkidle');

    // Verifikasi bahwa qr_code sudah aktif ("Ya" dipilih di toggle button)
    await expect(page.locator('label.tipe:has(input[name="qr_code"][value="1"])'))
      .toHaveClass(/active/, { timeout: 5000 });

    // ── BAGIAN 2: Buat surat di halaman Cetak Surat (/surat) ────────────────

    // Navigasi langsung ke form surat id=268 (Perintah Perjalanan Dinas, jenis=3, qr_code=1)
    // url_surat = 'sistem-surat-perintah-perjalanan-dinas'
    // Hindari race condition DataTable search (baris lama masih di DOM saat waitForSelector)
    await page.goto('/surat/form/sistem-surat-perintah-perjalanan-dinas');
    await page.waitForLoadState('networkidle');

    // ── BAGIAN 3: Isi form surat ────────────────────────────────────────────

    // Buka dropdown Select2 penduduk dan cari dengan NIK
    await page.getByText('-- Cari NIK / Tag ID Card /').click();
    await page.locator('input[type="search"]').fill(PENDUDUK_NIK);
    await page.waitForSelector('.select2-results__option', { timeout: 10000 });
    await page.locator('.select2-results__option').first().click();

    // Isi kode_isian required
    await page.getByRole('textbox', { name: 'Pangkat dan Golongan' }).fill('III/a');
    await page.getByRole('textbox', { name: 'Jabatan/Instansi' }).fill('Staf Desa');
    await page.getByRole('textbox', { name: 'Tingkat Biaya Perjalanan' }).fill('1000000');
    await page.getByRole('textbox', { name: 'Maksud Perjalanan Dinas' }).fill('Koordinasi');
    await page.getByRole('textbox', { name: 'Tempat Tujuan' }).fill('Ibukota Kabupaten');
    await page.getByRole('textbox', { name: 'Alat Angkut Yang Digunakan' }).fill('Motor');
    await page.getByRole('textbox', { name: 'Keterangan Lain' }).fill('-');

    // Tanggal Berangkat: buka datepicker lalu pilih hari ke-1
    await page.getByRole('textbox', { name: 'Tanggal Berangkat' }).click();
    await page.getByRole('cell', { name: '1' }).first().click();

    // Tanggal Kembali: buka datepicker lalu pilih hari ke-5
    await page.getByRole('textbox', { name: 'Tanggal Kembali' }).click();
    await page.getByRole('cell', { name: '5' }).first().click();

    // Submit form ke pratinjau
    await page.getByRole('button', { name: /Lanjut/i }).click();
    await page.waitForLoadState('networkidle');

    // ── BAGIAN 4: Verifikasi halaman konsep muncul ──────────────────────────

    // TinyMCE harus muncul di halaman konsep — jika QR code rusak biasanya crash di sini
    await page.waitForSelector('iframe.tox-edit-area__iframe', { timeout: 15000 });

    // Verifikasi ada link Tinjau PDF (elemen <a>, bukan <button>)
    await expect(page.locator('a#preview-pdf')).toBeVisible();
  });
});
