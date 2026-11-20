// Issue: #11266 — Filter dusun & tahun pada Statistik Akta Kematian tidak diteruskan
// ke Data Riwayat Mutasi Penduduk
// https://github.com/OpenSID/OpenSID/issues/11266
//
// Diport dari rilis-dev (e2e/bugs/issues#11266.spec.ts) saat migrasi suite er-laravel.
// Penyesuaian terhadap strategi & seed:
//   - Pakai fixture regressionTest (login per-test) menggantikan storageState.
//   - Selektor Select2 dusun diperbaiki: ketik nama dusun di kotak cari (Prinsip Select2),
//     bukan mengandalkan posisi nth-child yang rapuh.
//   - Data dusun memakai nilai yang ADA di seed (KEDEMANGAN MAKMUR di Desa Tunas Mudo),
//     bukan 'BALIAK BUKIK' yang spesifik database lokal lama (Prinsip: tidak hardcode data lokal).
// TRIASE: alur popup count-link bergantung data akta kematian pada seed — verifikasi saat
//         menjalankan folder statistik/ dengan DB ter-reset.
import { test, expect } from '../../../utils/regressionTest';

const DUSUN = 'KEDEMANGAN MAKMUR';

test.describe('Bug/error: Filter dusun & tahun Statistik Akta Kematian diteruskan ke Riwayat Mutasi Penduduk #11266', () => {
  test('fix: filter dusun pada statistik akta kematian diteruskan ke popup riwayat mutasi', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11266',
    },
  }, async ({ page }) => {
    await page.goto('statistik/penduduk/akta-kematian');
    await page.waitForLoadState('domcontentloaded');

    // Pilih dusun via Select2: buka, ketik nama dusun, pilih dari hasil.
    await page.locator('.select2-selection__arrow').first().click();
    await page.locator('.select2-search__field').fill(DUSUN);
    await page.getByRole('treeitem', { name: DUSUN }).click();

    // Klik salah satu angka jumlah pada tabel yang membuka popup Riwayat Mutasi Penduduk.
    const popupPromise = page.waitForEvent('popup');
    await page.locator('#tabeldata tbody a').first().click();
    const popup = await popupPromise;
    await popup.waitForLoadState('domcontentloaded');

    // Filter dusun harus ikut diteruskan ke halaman popup.
    await expect(popup.getByRole('textbox', { name: DUSUN })).toBeVisible({ timeout: 15000 });
  });
});
