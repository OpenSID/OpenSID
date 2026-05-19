import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

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

    const beforeTableText = (await tableBody.innerText()).trim();
    const selectedCategory = categoryOptions[0];

    const datatableResponse = page.waitForResponse((response) => {
      const request = response.request();

      return request.method() === 'POST'
        && response.url().includes('/kelompok/datatables')
        && response.status() === 200;
    }, { timeout: 10000 });

    await page.locator('span[aria-labelledby="select2-filter-container"]').click();
    await page.locator('.select2-results__option', { hasText: selectedCategory.label }).first().click();
    await datatableResponse;

    await expect(page.locator('#tabeldata_processing')).toBeHidden({ timeout: 10000 });
    await expect(filterSelect).toHaveValue(selectedCategory.value);

    const afterTableText = (await tableBody.innerText()).trim();
    expect(afterTableText).not.toBe(beforeTableText);

    await expect(page.locator('text=DataTables warning')).toHaveCount(0);
  });
});
