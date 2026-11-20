// Fitur: Acak Data menjalankan resolusi /periksa otomatis (default) sehingga
// database hasil acak lolos seluruh pemeriksaan halaman /periksa, dan halaman
// hasil melaporkan masalah yang diselesaikan.
//
// PERINGATAN: spec ini MENJALANKAN proses Acak yang menganonimkan SELURUH
// database opensid_playwright secara permanen (tidak dapat dipulihkan oleh
// beforeEach) — persis seperti dijelaskan pada acak-opsi-surat-sintetis.spec.ts.
// Karena itu spec ini DILEWATI secara default dan hanya berjalan bila dijalankan
// dengan sengaja terhadap database sekali-pakai:
//
//   ACAK_DESTRUCTIVE=1 npx playwright test tests/playwright/e2e/regression/database/acak-periksa-bersih.spec.ts
//
// Jangan pernah menjalankannya sebagai bagian dari suite penuh terhadap
// opensid_playwright bersama.
import { test, expect } from '../../../utils/regressionTest';

const DESTRUCTIVE = process.env.ACAK_DESTRUCTIVE === '1';

test.describe('Acak Data — database hasil lolos pemeriksaan /periksa', () => {
  test.skip(
    !DESTRUCTIVE,
    'Destruktif: menganonimkan seluruh database. Jalankan dengan ACAK_DESTRUCTIVE=1 pada database sekali-pakai.',
  );

  test('setelah acak, halaman hasil tidak melaporkan sisa masalah periksa', async ({ page }) => {
    // Render surat sintetis + pemrosesan foto bisa memakan beberapa menit.
    test.setTimeout(15 * 60 * 1000);

    await page.goto('acak-data');
    await expect(page.locator('#maincontent')).toContainText('Arsip Surat');

    // Pilih opsi paling ringan agar proses cepat (tidak mengubah perilaku periksa).
    await page.locator('input[name="foto_mode"][value="none"]').check();
    await page.locator('input[name="foto_artikel_mode"][value="none"]').check();
    await page.locator('input[name="surat_mode"][value="placeholder"]').check();

    // Konfirmasi wajib sebelum tombol proses aktif.
    await page.locator('#konfirmasi_sinkron').check();
    await page.locator('#konfirmasi_arsip').check();

    await page.locator('#btn-buka-modal-acak').click();
    const lanjut = page.locator('#btn-acak-lanjut');
    await expect(lanjut).toBeVisible();

    // Proses berjalan via SSE lalu mengarahkan ke halaman hasil saat selesai.
    await Promise.all([
      page.waitForURL('**/acak-data/hasil', { timeout: 15 * 60 * 1000 }),
      lanjut.click(),
    ]);

    const main = page.locator('#maincontent');

    // Bagian ringkasan resolusi periksa muncul.
    await expect(main).toContainText('Pemeriksaan database');

    // Inti pengujian: TIDAK ada sisa masalah periksa — database lolos semua cek.
    await expect(main).not.toContainText('Masalah periksa yang masih tersisa');
  });
});
