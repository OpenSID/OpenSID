import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../../storage/auth/admin.json'),
});

test.describe('Link "Daftar Lembaga" pada breadcrumb laman rincian #9493', () => {
  test('fix: perbaiki link breadcumb detail lembaga', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/9493',
    },
  }, async ({ page }) => {
    test.setTimeout(60000);

    await page.goto('lembaga');
    await expect(page.locator('a[title="Rincian"]').first()).toBeVisible({ timeout: 20000 });
    await page.locator('a[title="Rincian"]').first().click();

    const breadcrumbDaftarLembaga = page.getByRole('link', { name: 'Daftar Lembaga', exact: true });
    await expect(breadcrumbDaftarLembaga).toBeVisible();
    await breadcrumbDaftarLembaga.click();

    await expect(page.getByRole('heading', { name: 'Pengelolaan Lembaga' })).toBeVisible();
  });
});
