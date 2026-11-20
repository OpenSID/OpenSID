// Issue: #9490 — Detail shortcut menampilkan data yang tidak sesuai
// https://github.com/OpenSID/OpenSID/issues/9490
import { test, expect} from '../../../utils/regressionTest';


test.describe('Bug/error: DETAIL SHORTCUT MENAMPILKAN DATA YANG TIDAK SESUAI #9490', () => {
  test.beforeEach(async ({ page }) => {
    test.setTimeout(60000);

    // Create each shortcut via direct POST to avoid slow multi-form UI navigation.
    // Each iteration: load form to get fresh CSRF token, then POST the data directly.
    const shortcuts = [
      { judul: 'Penduduk Laki-laki',       raw_query: 'Penduduk Laki-laki',       warna: '#3c8dbc' },
      { judul: 'Penduduk Wanita',           raw_query: 'Penduduk Perempuan',       warna: '#ffffff' },
      { judul: 'Kepala Keluarga Laki-laki', raw_query: 'Kepala Keluarga Laki-laki', warna: '#de0d0d' },
      { judul: 'Kepala Keluarga Wanita',    raw_query: 'Kepala Keluarga Perempuan', warna: '#c42020' },
    ];

    for (const s of shortcuts) {
      await page.goto('shortcut/form');
      const csrfToken = await page.inputValue('[name="sidcsrf"]');
      await page.evaluate(async ({ judul, raw_query, warna, csrfToken }) => {
        const body = new URLSearchParams({ sidcsrf: csrfToken, judul, raw_query, icon: 'fa-home', warna, urut: '0', status: '1' });
        await fetch('/shortcut/insert', { method: 'POST', body: body.toString(), headers: { 'Content-Type': 'application/x-www-form-urlencoded' }, credentials: 'include', redirect: 'follow' });
      }, { ...s, csrfToken });
    }

    await page.goto('beranda');
    await expect(page.getByText('Penduduk Laki-laki').first()).toBeVisible();
  });

  test.afterEach(async ({ page }) => {
    // The shortcut DataTable uses lengthMenu: [10, 25, 50, 100] — no "Semua" option, so
    // UI bulk-delete across pages is unreliable. Instead: search for each shortcut by name,
    // read its data-id, and navigate to the CI3 delete URL (GET — no CSRF required).
    const testNames = ['Penduduk Laki-laki', 'Penduduk Wanita', 'Kepala Keluarga Laki-laki', 'Kepala Keluarga Wanita'];

    for (const name of testNames) {
      await page.goto('shortcut');
      await page.locator('.dataTables_filter input').fill(name);
      const row = page.locator('#tabeldata tbody tr').filter({ hasText: name }).first();
      const id = await row.getAttribute('data-id', { timeout: 10000 });
      if (id) {
        await page.goto(`shortcut/delete/${id}`);
      }
    }
  });

  test('fix: perbaiki link shortcut', {
    annotation: {
        type: 'issue',
        description: 'https://github.com/OpenSID/OpenSID/issues/9490'
    }
  }, async ({ page }) => {
    // beforeEach already navigated to beranda — widgets should be visible
    await expect(page.getByText('Penduduk Laki-laki').first()).toBeVisible();

    // Verify each widget footer link goes to the correct filtered page.
    // The regression was: links pointed to the wrong page (no filter applied).
    await page.locator('div.small-box:has-text("Penduduk Laki-laki")').locator('.small-box-footer').click();
    await expect(page).toHaveURL(/penduduk.*sex=1/, { timeout: 15000 });

    await page.goto('beranda');
    await page.locator('div.small-box:has-text("Penduduk Wanita")').locator('.small-box-footer').click();
    await expect(page).toHaveURL(/penduduk.*sex=2/, { timeout: 15000 });

    await page.goto('beranda');
    await page.locator('div.small-box:has-text("Kepala Keluarga Laki-laki")').locator('.small-box-footer').click();
    await expect(page).toHaveURL(/keluarga.*sex=1/, { timeout: 15000 });

    await page.goto('beranda');
    await page.locator('div.small-box:has-text("Kepala Keluarga Wanita")').locator('.small-box-footer').click();
    await expect(page).toHaveURL(/keluarga.*sex=2/, { timeout: 15000 });
  });
});
