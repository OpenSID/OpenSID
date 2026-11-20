// Issue: #10859 — Sebutan dusun tidak tampil pada pilihan lokasi pembangunan
// https://github.com/OpenSID/OpenSID/issues/10859
import { test, expect } from '../../../utils/regressionTest';
import { Laravel } from '@test/utils/laravel';
import type { Page } from '@playwright/test';

// Prefix opsi memakai sebutan dusun yang DIKONFIGURASI (mis. nagari memakai
// "Jorong", bukan "Dusun"). Jangan hardcode "DUSUN" — baca dari setting.
let prefix: RegExp;

test.beforeAll(async () => {
  const rows = (await Laravel.query(
    "SELECT value FROM setting_aplikasi WHERE `key` = 'sebutan_dusun' LIMIT 1",
  )) as Array<{ value: string }>;
  const sebutan = (rows[0]?.value ?? 'Dusun').toUpperCase();
  // Format opsi: "<SEBUTAN> <NAMA> - RW <..> / RT <..>"
  prefix = new RegExp(`^${sebutan}\\s`);
});

// Opsi <option> ada di <select> tersembunyi select2 — dibaca langsung tanpa
// perlu meng-klik (klik native select2-hidden akan ter-intercept).
const optionsLoc = (page: Page) => page.locator('#id_lokasi option:not([value=""])');

test.describe('Bug: Sebutan Dusun Tidak Tampil pada Pilihan Lokasi Pembangunan #10859', () => {
  test('fix: opsi lokasi memakai prefix sebutan dusun', {
    annotation: { type: 'issue', description: 'https://github.com/OpenSID/OpenSID/issues/10859' },
  }, async ({ page }) => {
    await page.goto('admin_pembangunan/form');
    await page.locator('#id_lokasi').waitFor({ state: 'attached', timeout: 15000 });

    const texts = (await optionsLoc(page).allTextContents()).map((t) => t.trim());
    expect(texts.length).toBeGreaterThan(0);
    expect(texts[0]).toMatch(prefix);
  });

  test('fix: semua opsi lokasi berformat "<sebutan> <nama> - RW.. / RT.."', {
    annotation: { type: 'issue', description: 'https://github.com/OpenSID/OpenSID/issues/10859' },
  }, async ({ page }) => {
    await page.goto('admin_pembangunan/form');
    await page.locator('#id_lokasi').waitFor({ state: 'attached', timeout: 15000 });

    const texts = (await optionsLoc(page).allTextContents()).map((t) => t.trim());
    expect(texts.length).toBeGreaterThan(0);
    for (const t of texts) {
      expect(t).toMatch(prefix);
      expect(t.length).toBeGreaterThan(6);
    }
  });

  test('fix: opsi lokasi (dengan prefix sebutan) dapat dipilih', {
    annotation: { type: 'issue', description: 'https://github.com/OpenSID/OpenSID/issues/10859' },
  }, async ({ page }) => {
    await page.goto('admin_pembangunan/form');
    const dropdown = page.locator('#id_lokasi');
    await dropdown.waitFor({ state: 'attached', timeout: 15000 });

    const firstValid = optionsLoc(page).first();
    const value = await firstValid.getAttribute('value');
    expect((await firstValid.textContent())?.trim()).toMatch(prefix);

    await dropdown.selectOption(value ?? '');
    expect(await dropdown.inputValue()).toBe(value);
  });
});
