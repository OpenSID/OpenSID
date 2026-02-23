import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../../storage/auth/admin.json'),
});

test.describe('Info Sistem - Log Aktivitas', () => {
  test('lazy load, filter siap, dan modal detail berfungsi', async ({ page }) => {
    await page.goto('info_sistem#log_aktifitas');

    const spinner = page.locator('#log_aktifitas .fa-refresh.fa-spin');
    const table = page.locator('#tabel-logaktifitas');

    await expect(table).toBeVisible({ timeout: 15000 });
    await expect(spinner).toBeHidden({ timeout: 15000 });

    await expect(page.locator('#log_name')).toBeEnabled();
    await expect(page.locator('#log_event')).toBeEnabled();
    await expect(page.locator('#username')).toBeEnabled();

    await expect(page.locator('#tabel-logaktifitas tbody tr').first()).toBeVisible({ timeout: 15000 });
    await expect(page.locator('#tabel-logaktifitas tbody tr td:nth-child(4) .label').first()).toBeVisible();

    const createdAtCell = page.locator('#tabel-logaktifitas tbody tr td:nth-child(9)').first();
    await expect(createdAtCell).toBeVisible();
    await expect(createdAtCell).toHaveText(/[0-9]{1,2} [A-Za-z]+ 20[0-9]{2}/);

    const detailButton = page.locator('#tabel-logaktifitas .btn-detail-log').first();
    await detailButton.click();

    await expect(page.locator('#logDetailModal')).toBeVisible();
    await expect(page.locator('#json-diff-output')).toBeVisible();
  });
});
