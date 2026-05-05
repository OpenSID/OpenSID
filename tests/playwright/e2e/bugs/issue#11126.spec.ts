import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('[BUG] DataTables Ajax Error & 404 Not Found pada Halaman Backup Inkremental #11126', () => {
  test('fix: halaman Backup Inkremental dapat dimuat tanpa error 404', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11126',
    },
  }, async ({ page }) => {
    // Collect console errors sebelum navigate
    const consoleErrors: { type: string; message: string }[] = [];
    page.on('console', msg => {
      consoleErrors.push({
        type: msg.type(),
        message: msg.text(),
      });
    });

    // Navigate ke halaman Backup Inkremental
    const response = await page.goto('database/desa_inkremental');

    // Verifikasi halaman loaded dengan status 200 (bukan 404)
    expect(response?.status()).toBe(200);

    // Verifikasi halaman loaded dengan benar
    await expect(page).toHaveTitle(/Database/);
    await expect(page.locator('h1')).toContainText('Database');
    await expect(page.locator('small')).toContainText('Backup Inkremental');

    // Verifikasi breadcrumb
    await expect(page.locator('.breadcrumb-item')).toContainText('Pengaturan Database');
    await expect(page.locator('.breadcrumb-item.active')).toContainText('Backup Inkremental');

    // Verifikasi table header ada
    await expect(page.locator('table thead')).toBeVisible();
    const headers = await page.locator('table thead th').allTextContents();
    expect(headers).toContain('No');
    expect(headers).toContain('Aksi');
    expect(headers).toContain('Ukuran (MB)');
    expect(headers).toContain('Tanggal Backup');

    // Wait untuk DataTables AJAX request dan render selesai
    await page.waitForLoadState('networkidle');
    await page.waitForTimeout(500);

    // Verifikasi DataTable sudah render
    const table = page.locator('table.dataTable');
    await expect(table).toBeVisible();

    // Verifikasi tidak ada 404 error di console
    const notFoundErrors = consoleErrors.filter(
      err => err.type === 'error' && err.message.includes('404')
    );
    expect(notFoundErrors.length).toBe(0);

    // Verifikasi tidak ada error popup/modal
    const errorModals = await page.locator('.modal.in, .alert-danger').count();
    expect(errorModals).toBe(0);
  });

  test('fix: route GET /desa_inkremental menampilkan view halaman', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11126',
    },
  }, async ({ page }) => {
    // Navigate ke halaman
    const response = await page.goto('database/desa_inkremental');

    // Verifikasi response status 200 (bukan 404)
    expect(response?.status()).toBe(200);

    // Verifikasi page loaded
    await expect(page.locator('h1')).toBeVisible();
  });

  test('fix: route POST /desa_inkremental_datatables mengembalikan JSON dengan format DataTables', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11126',
    },
  }, async ({ page }) => {
    // Intercept AJAX response
    let datatableResponse: any = null;
    page.on('response', response => {
      if (response.url().includes('desa_inkremental_datatables') &&
          response.request().method() === 'POST') {
        response.json().then(json => {
          datatableResponse = json;
        });
      }
    });

    // Navigate ke halaman dulu (untuk set session/auth)
    await page.goto('database/desa_inkremental');

    // Wait untuk AJAX request selesai
    await page.waitForLoadState('networkidle');
    await page.waitForTimeout(500);

    // Verifikasi response JSON tersedia
    expect(datatableResponse).toBeDefined();

    // Verifikasi response memiliki struktur DataTables
    expect(datatableResponse).toHaveProperty('draw');
    expect(datatableResponse).toHaveProperty('recordsTotal');
    expect(datatableResponse).toHaveProperty('recordsFiltered');
    expect(datatableResponse).toHaveProperty('data');
    expect(Array.isArray(datatableResponse.data)).toBe(true);
  });

  test('fix: DataTables dapat display tabel tanpa error popup', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11126',
    },
  }, async ({ page }) => {
    // Navigate ke halaman
    await page.goto('database/desa_inkremental');

    // Wait untuk DataTables selesai loading
    await page.waitForLoadState('networkidle');

    // Verifikasi tidak ada error popup/modal
    const errorModals = await page.locator('.modal.in, [role="alertdialog"]').count();
    expect(errorModals).toBe(0);

    // Verifikasi tidak ada DataTables error message
    const datatableErrors = await page.locator('.dataTables_empty').isVisible().catch(() => false);
    // Jika tidak ada error, akan menampilkan tabel atau pesan "Tidak ada data"

    // Verifikasi DataTable wrapper ada
    const datatableWrapper = page.locator('.dataTables_wrapper');
    await expect(datatableWrapper).toBeVisible();
  });
});
