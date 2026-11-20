// Bug: "Cetak Surat PDF" pada arsip layanan (/keluar) menampilkan dump PDF mentah
// alih-alih me-render PDF inline di browser.
//
// Akar masalah: ambilBerkas() cabang inline ($tampil=true) mengirim file via
// header()+readfile()+return. Pada stack hibrida CI3→Laravel, Laravel menimpa
// Content-Type menjadi text/html, sehingga byte PDF tampil sebagai teks mentah.
// Perbaikan mengalirkan output melalui CI Output (set_content_type + set_output)
// agar CodeIgniterResponseMiddleware mempertahankan mime_type sebagai Content-Type.
//
// Test ini menyiapkan satu baris log_surat + file PDF arsip yang sesuai, lalu
// memanggil endpoint inline dan memastikan Content-Type respons adalah
// application/pdf (bukan text/html) dan body benar-benar PDF. Self-restoring:
// baris DB dan file dibersihkan di afterEach.
import { test, expect } from '../../../utils/regressionTest';
import { Laravel } from '../../../utils/laravel';
import fs from 'fs';
import path from 'path';

const ARSIP_DIR = path.resolve(__dirname, '../../../../..', 'desa/arsip');
const NAMA_SURAT = 'pw_inline_content_type_test.pdf';
const ARSIP_PATH = path.join(ARSIP_DIR, NAMA_SURAT);

// PDF minimal yang valid agar mime_content_type() mendeteksi application/pdf.
const MINIMAL_PDF = Buffer.from(
  '%PDF-1.4\n1 0 obj<</Type/Catalog/Pages 2 0 R>>endobj\n' +
  '2 0 obj<</Type/Pages/Kids[3 0 R]/Count 1>>endobj\n' +
  '3 0 obj<</Type/Page/MediaBox[0 0 595 842]/Parent 2 0 R>>endobj\n' +
  'trailer<</Root 1 0 R>>\n%%EOF',
  'latin1',
);

let logSuratId: number;

test.describe('Bug: Cetak Surat PDF inline menampilkan PDF mentah (#keluar/unduh/tinymce)', () => {
  test.beforeEach(async () => {
    fs.mkdirSync(ARSIP_DIR, { recursive: true });
    fs.writeFileSync(ARSIP_PATH, MINIMAL_PDF);

    await Laravel.query(
      'INSERT INTO log_surat (config_id, nama_surat, status, is_draft, kecamatan, tanggal) VALUES (1, ?, 1, 0, 1, NOW())',
      [NAMA_SURAT],
    );
    const rows = (await Laravel.select(
      'SELECT id FROM log_surat WHERE nama_surat = ? ORDER BY id DESC LIMIT 1',
      [NAMA_SURAT],
    )) as Array<{ id: number }>;
    logSuratId = rows[0].id;
  });

  test.afterEach(async () => {
    if (logSuratId) {
      await Laravel.query('DELETE FROM log_surat WHERE id = ?', [logSuratId]);
    }
    if (fs.existsSync(ARSIP_PATH)) {
      fs.unlinkSync(ARSIP_PATH);
    }
  });

  test('endpoint inline mengembalikan Content-Type application/pdf, bukan text/html', async ({ page }) => {
    // page.request memakai cookie sesi dari context (terautentikasi). page.goto
    // tidak cocok di sini karena Content-Type application/pdf memicu unduhan di
    // Chromium sehingga navigasi di-abort.
    const resp = await page.request.get(
      `${process.env.PLAYWRIGHT_BASE_URL}/keluar/unduh/tinymce/${logSuratId}`,
    );

    expect(resp.status()).toBe(200);

    const contentType = resp.headers()['content-type'] ?? '';
    expect(contentType).toContain('application/pdf');
    expect(contentType).not.toContain('text/html');

    // Body benar-benar PDF (magic %PDF) — bukan halaman HTML login/error,
    // sekaligus membuktikan request terautentikasi.
    const body = await resp.body();
    expect(body.subarray(0, 4).toString('latin1')).toBe('%PDF');
  });
});
