// Issue: #9908 — Notifikasi ubah status penduduk
// https://github.com/OpenSID/OpenSID/issues/9908
import { test, expect } from '../../../utils/regressionTest';


test.describe('Bug/error: Notifikasi Ubah Status #9908', () => {
  test('fix: perbaiki Notifikasi Ubah Status dasar', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/9908',
    },
  }, async ({ page }) => {
    await page.goto('penduduk');
    await page.waitForLoadState('networkidle');
    // Pilih Aksi dropdown uses <a class="dropdown-toggle">, not <button>
    await page.locator('table#tabeldata tbody tr:first-child a.dropdown-toggle').click();
    await page.getByRole('link', { name: ' Ubah Status Dasar' }).click();
    await expect(page.locator('#modalBox')).toBeVisible({ timeout: 10000 });
    await page.getByTitle('Pilih Status Dasar').click();
    // Use "Hilang" which is always available regardless of kk_level
    await page.locator('.select2-results__option', { hasText: 'Hilang' }).click();
    await page.waitForSelector('[placeholder="Catatan"]');
    await page.getByPlaceholder('Catatan').fill('test hilang');
    await page.locator('#ok').click();
    await expect(page.locator('#notifikasi')).toContainText('Status dasar penduduk berhasil diubah', { timeout: 10000 });
  });
});
