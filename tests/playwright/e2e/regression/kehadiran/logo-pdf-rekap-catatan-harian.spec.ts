// Issue: #11297 — Logo PDF Rekap Catatan Harian masih memakai logo default OpenSID
// https://github.com/OpenSID/OpenSID/issues/11297
//
// Diport dari rilis-dev (e2e/bugs/issue#11297.spec.ts) saat migrasi suite er-laravel.
// Penyesuaian: pakai fixture regressionTest (login per-test) menggantikan storageState,
// dan path util mengikuti struktur regression/<domain>/.
import { test, expect } from '../../../utils/regressionTest';
import { Laravel } from '../../../utils/laravel';

test.describe('Bug/error: Logo PDF Rekap Catatan Harian masih memakai logo default OpenSID #11297', () => {
  const logoDesa = 'file.webp';
  let configId: number | null = null;
  let logoAwal: string | null = null;

  test.beforeAll(async () => {
    const config = await Laravel.select(
      `SELECT id, logo FROM config WHERE app_key IS NOT NULL ORDER BY id ASC LIMIT 1`
    ) as Array<{ id: number; logo: string | null }>;

    if (config.length === 0) {
      return;
    }

    configId = config[0].id;
    logoAwal = config[0].logo;

    await Laravel.query(`UPDATE config SET logo = ? WHERE id = ?`, [logoDesa, configId]);
    await Laravel.artisan('cache:clear');
  });

  test.afterAll(async () => {
    if (!configId) {
      return;
    }

    await Laravel.query(`UPDATE config SET logo = ? WHERE id = ?`, [logoAwal, configId]);
    await Laravel.artisan('cache:clear');
  });

  test('fix: halaman cetak rekap catatan harian memakai logo dari identitas desa', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11297',
    },
  }, async ({ page }) => {
    test.skip(!configId, 'Data config desa tidak tersedia');

    await page.goto('kehadiran_rekap_catatan/cetak?bulan=04&tahun=2026');
    await page.waitForLoadState('domcontentloaded');

    const logo = page.locator('img.logo[alt="logo-desa"]').first();
    await expect(logo).toBeVisible();

    await expect(logo).toHaveAttribute('src', /desa\/logo\/file\.webp$/);
    await expect(logo).not.toHaveAttribute('src', /assets\/files\/logo\/opensid_logo\.png$/);
  });
});
