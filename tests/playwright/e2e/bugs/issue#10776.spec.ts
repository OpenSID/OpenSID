import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Tampilan E-Stunting Web #10776', () => {
  test('fix: perbaikan filter stunting tidak berfungsi', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10776',
    },
  }, async ({ page }) => {
    await page.goto('data-kesehatan/stunting');
    await expect(page.getByText('Anak Periksa 1')).toBeVisible();
    await page.locator('#kuartal').selectOption('2');
    await page.getByRole('button', { name: ' Cari' }).click();
    await expect(page.getByText('Anak Periksa 0')).toBeVisible();
    await page.locator('#kuartal').selectOption('1');
    await page.locator('#id_posyandu').selectOption('2');
    await page.getByRole('button', { name: ' Cari' }).click();
    await expect(page.getByText('Anak Periksa 0')).toBeVisible();
    await page.locator('#id_posyandu').selectOption('1');
    await page.getByRole('button', { name: ' Cari' }).click();
    await expect(page.getByText('Anak Periksa 1')).toBeVisible();
  });
});
