// Issue: #9776 — Hapus penduduk error
// https://github.com/OpenSID/OpenSID/issues/9776
import { test, expect } from '../../../utils/regressionTest';
import { Laravel } from '../../../utils/laravel';


test.describe('Bug/error: Hapus Penduduk erorr #9776', () => {
  test.beforeEach(async () => {
    // Re-insert the seed row before each run so the test is idempotent.
    // INSERT IGNORE is a no-op if the row already exists; it re-inserts after a previous run deleted it.
    await Laravel.query(
      `INSERT IGNORE INTO tweb_penduduk
         (id, config_id, nama, nik, id_kk, kk_level, id_rtm, rtm_level, sex, status_dasar,
          agama_id, pendidikan_kk_id, pekerjaan_id, status_kawin, golongan_darah_id,
          tempatlahir, tanggallahir, id_cluster)
       VALUES (160999, 1, 'Test Hapus Penduduk', '9999999999999999', 39, 4, NULL, NULL, 1, 1,
               1, 1, 1, 1, 1, 'TUNAS MUDO', '2000-01-01', 45)`
    );
  });

  test('fix: perbaiki hapus penduduk yang gagal', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/9776',
    },
  }, async ({ page }) => {
    // Use seed-specific penduduk (NIK 9999999999999999, id=160999) that has no log_surat/bantuan/pamong.
    // beforeEach re-inserts this row automatically so the test can run multiple times without a DB reset.
    await page.goto('penduduk');
    // Wait for the DataTable to render (never use networkidle — background polling blocks forever).
    await page.waitForSelector('table#tabeldata tbody tr', { timeout: 15000 });
    // Filter DataTable to this specific NIK so we know which row we're deleting
    await page.locator('.dataTables_filter input[type="search"]').fill('9999999999999999');
    await page.waitForTimeout(600);
    await expect(page.locator('table#tabeldata tbody tr')).toHaveCount(1, { timeout: 10000 });
    // Open action dropdown for the first (only) row
    await page.locator('table#tabeldata tbody tr:first-child a.dropdown-toggle').click();
    // Hapus link is inside the dropdown
    await page.locator('table#tabeldata tbody tr:first-child .dropdown-menu a[title="Hapus"]').click();
    // Wait for Bootstrap shown.bs.modal to fire — it resets the input and attaches the
    // input event handler; the modal auto-focuses the input 150ms after shown event
    await page.waitForFunction(
      () => document.querySelector('#confirm-input') === document.activeElement,
      { timeout: 5000 }
    );
    await page.locator('#confirm-input').fill('HAPUS');
    await expect(page.locator('#confirm-delete #ok-delete')).not.toBeDisabled();
    // btn-ok navigates via href set by show.bs.modal handler to penduduk/delete/{id}
    await Promise.all([
      page.waitForURL(/penduduk/, { timeout: 10000 }),
      page.locator('#confirm-delete .btn-ok').click(),
    ]);
    await expect(page.locator('#notifikasi')).toBeVisible({ timeout: 10000 });
    await expect(page.locator('#notifikasi')).toContainText('Berhasil');
    await expect(page.locator('#notifikasi p')).toContainText('Penduduk berhasil dihapus');
  });
});
