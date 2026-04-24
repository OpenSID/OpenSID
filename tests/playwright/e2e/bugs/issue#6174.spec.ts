import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: [SECURITY-CRITICAL] SSRF & POST Data Hijacking via api_opendk_server pada Modul Sinkronisasi #6174', () => {
  test('fix: perbaiki validasi input api_opendk_server blacklisted', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/premium/issues/6174',
    },
  }, async ({ page }) => {
    await page.goto('sinkronisasi#tab_buat_key');

    await page.getByRole('link', { name: 'Pengaturan', exact: true }).click();
    await page.getByText('Ya', { exact: true }).click();
    await page.locator('#api_opendk_server').click();
    await page.locator('#api_opendk_server').fill('http://127.0.0.1');
    await page.getByRole('textbox', { name: 'Silakan Masukkan API Key' }).click();
    await page.getByRole('textbox', { name: 'Silakan Masukkan API Key' }).fill('random-key');
    await page.getByRole('button', { name: ' Simpan' }).click();
    await expect(page.getByText('Domain lokal tidak diizinkan.')).toBeVisible();
  });
});
