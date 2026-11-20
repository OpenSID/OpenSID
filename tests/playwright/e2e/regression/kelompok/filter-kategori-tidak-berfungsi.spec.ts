// Issue: #11215 — Filter kategori kelompok tidak berfungsi
// https://github.com/OpenSID/OpenSID/issues/11215
import { test, expect } from '../../../utils/regressionTest';


test.describe('Bug/error: filter kategori kelompok tidak berfungsi #11215', () => {
  test('filter kategori harus mengubah data pada DataTables halaman kelompok', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11215',
    },
  }, async ({ page }) => {
    test.setTimeout(30000);

    await page.goto('/kelompok');
    await page.waitForLoadState('networkidle');

    const table = page.locator('#tabeldata');
    const tableBody = table.locator('tbody');
    const filterSelect = page.locator('select#filter');

    await expect(table).toBeVisible({ timeout: 10000 });
    await expect(filterSelect).toBeAttached({ timeout: 10000 });
    await expect(tableBody.locator('tr').first()).toBeVisible({ timeout: 10000 });

    const categoryOptions = await filterSelect.locator('option').evaluateAll((options) =>
      options
        .map((option) => ({
          value: option.getAttribute('value') ?? '',
          label: option.textContent?.trim() ?? '',
        }))
        .filter((option) => option.value !== '' && option.label !== '')
    );

    test.skip(categoryOptions.length === 0, 'Tidak ada kategori kelompok untuk diuji.');

    const selectedCategory = categoryOptions[0];

    // Pantau AJAX request yang dikirim saat filter dipilih
    const datatableResponse = page.waitForResponse((response) => {
      const request = response.request();
      return request.method() === 'POST'
        && response.url().includes('/kelompok/datatables')
        && response.status() === 200;
    }, { timeout: 10000 });

    await page.locator('span[aria-labelledby="select2-filter-container"]').click();
    await page.locator('.select2-results__option', { hasText: selectedCategory.label }).first().click();

    // Verifikasi filter berhasil memicu AJAX request ke server (ini yang diuji bug #11215)
    await datatableResponse;

    await expect(page.locator('#tabeldata_processing')).toBeHidden({ timeout: 10000 });

    // Verifikasi filter menyimpan nilai yang dipilih
    await expect(filterSelect).toHaveValue(selectedCategory.value);

    // Verifikasi tidak ada DataTables warning (AJAX response valid)
    await expect(page.locator('text=DataTables warning')).toHaveCount(0);
  });
});
