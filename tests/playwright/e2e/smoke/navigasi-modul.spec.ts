// Smoke Test — Additional: Navigasi Modul Utama
// Verifies that all major admin modules load without 500 errors.
// A quick sanity check for every monthly release.
import { test, expect } from './smokeTest';

/** Assert a page loads at the given relative URL without a 5xx server error. */
async function assertModuleLoads(page: import('@playwright/test').Page, url: string): Promise<void> {
  const resp = await page.goto(url);
  // Allow 200 (loaded) or 302/301 (redirect — e.g. to sub-page). 404 and 500 are failures.
  expect(resp?.status(), `${url} should not return 500`).not.toBe(500);
  expect(resp?.status(), `${url} should not return 404`).not.toBe(404);
  await page.waitForLoadState('networkidle');
  // No error modal or server-error page
  await expect(page.locator('.alert-danger'), `${url} should not have error alert`).toHaveCount(0);
}

test.describe('Smoke: Navigasi Modul Utama', () => {
  // ── Modul yang sudah ada ────────────────────────────────────────────────

  test('modul Beranda dapat diakses', async ({ page }) => {
    test.setTimeout(20000);
    await assertModuleLoads(page, 'beranda');
    await expect(page.getByRole('heading', { name: 'Tentang OpenSID' })).toBeVisible();
  });

  test('modul Keluarga dapat diakses', async ({ page }) => {
    test.setTimeout(20000);
    await assertModuleLoads(page, 'keluarga');
  });

  test('modul Statistik dapat diakses', async ({ page }) => {
    test.setTimeout(20000);
    await assertModuleLoads(page, 'statistik');
  });

  test('modul Wilayah dapat diakses', async ({ page }) => {
    test.setTimeout(20000);
    await assertModuleLoads(page, 'wilayah');
  });

  test('modul Pengguna (man_user) dapat diakses', async ({ page }) => {
    test.setTimeout(20000);
    await assertModuleLoads(page, 'man_user');
  });

  test('modul Database dapat diakses', async ({ page }) => {
    test.setTimeout(20000);
    await assertModuleLoads(page, 'database/desa_inkremental');
    await expect(page.locator('h1').first()).toBeVisible();
  });

  test('modul Buku Tamu dapat diakses', async ({ page }) => {
    test.setTimeout(20000);
    await assertModuleLoads(page, 'buku_tamu');
  });

  test('modul Keuangan dapat diakses', async ({ page }) => {
    test.setTimeout(20000);
    await assertModuleLoads(page, 'keuangan_manual');
  });

  test('modul Galeri dapat diakses', async ({ page }) => {
    test.setTimeout(20000);
    await assertModuleLoads(page, 'gallery');
  });

  test('modul Info Sistem dapat diakses', async ({ page }) => {
    test.setTimeout(20000);
    await assertModuleLoads(page, 'info_sistem');
  });

  // ── Modul baru ──────────────────────────────────────────────────────────

  test('modul Analisis dapat diakses', async ({ page }) => {
    test.setTimeout(20000);
    await assertModuleLoads(page, 'analisis_master');
  });

  test('modul Bumindes (Penduduk Induk) dapat diakses', async ({ page }) => {
    test.setTimeout(20000);
    await assertModuleLoads(page, 'bumindes_penduduk_induk');
  });

  test('modul Dokumen dapat diakses', async ({ page }) => {
    test.setTimeout(20000);
    await assertModuleLoads(page, 'dokumen');
  });

  test('modul Inventaris dapat diakses', async ({ page }) => {
    test.setTimeout(20000);
    await assertModuleLoads(page, 'inventaris_gedung');
  });

  test('modul Kehadiran dapat diakses', async ({ page }) => {
    test.setTimeout(20000);
    await assertModuleLoads(page, 'kehadiran_rekapitulasi');
  });

  test('modul Kelompok dapat diakses', async ({ page }) => {
    test.setTimeout(20000);
    await assertModuleLoads(page, 'kelompok');
  });

  test('modul Lapak dapat diakses', async ({ page }) => {
    test.setTimeout(20000);
    await assertModuleLoads(page, 'lapak_admin/pelapak');
  });

  test('modul Laporan dapat diakses', async ({ page }) => {
    test.setTimeout(20000);
    await assertModuleLoads(page, 'laporan');
  });

  test('modul Pembangunan dapat diakses', async ({ page }) => {
    test.setTimeout(20000);
    await assertModuleLoads(page, 'admin_pembangunan');
  });

  test('modul Pengaduan dapat diakses', async ({ page }) => {
    test.setTimeout(20000);
    await assertModuleLoads(page, 'pengaduan');
  });

  test('modul Periksa dapat diakses', async ({ page }) => {
    test.setTimeout(20000);
    await assertModuleLoads(page, 'periksa');
  });

  test('modul Pengurus dapat diakses', async ({ page }) => {
    test.setTimeout(20000);
    await assertModuleLoads(page, 'pengurus');
  });

  test('modul Program Bantuan dapat diakses', async ({ page }) => {
    test.setTimeout(20000);
    await assertModuleLoads(page, 'program_bantuan');
  });

  test('modul RTM dapat diakses', async ({ page }) => {
    test.setTimeout(20000);
    await assertModuleLoads(page, 'rtm');
  });

  test('modul Stunting dapat diakses', async ({ page }) => {
    test.setTimeout(20000);
    await assertModuleLoads(page, 'stunting/pemantauan_ibu_hamil');
  });

  test('modul Suplemen dapat diakses', async ({ page }) => {
    test.setTimeout(20000);
    await assertModuleLoads(page, 'suplemen');
  });

  test('modul Website Desa (cdesa) dapat diakses', async ({ page }) => {
    test.setTimeout(20000);
    await assertModuleLoads(page, 'cdesa');
  });
});
