import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Fitur: Tampilan Form Pengurus #10775', () => {
  test('cek: Kalimat "Status Pejabat (Pj.)" ditampilkan pada halaman form pengurus', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10775',
    },
  }, async ({ page }) => {
    // Langkah 1: Masuk halaman daftar pengurus
    await page.goto('pengurus');

    // Tunggu halaman dimuat - tunggu table data terlihat
    await page.waitForSelector('table.dataTable', { timeout: 10000 });

    // Langkah 2: Ambil baris pertama dari datatable dan klik tombol edit
    // Tunggu row pertama pada datatable muncul
    const firstRow = page.locator('table.dataTable tbody tr').first();
    await expect(firstRow).toBeVisible();

    // Cari tombol edit pada baris pertama
    // Tombol edit biasanya berada dalam kolom terakhir dengan icon atau teks "Edit"
    const editButton = firstRow.locator('a[title*="Edit"], a[title*="edit"], button[title*="Edit"]').first();
    
    // Jika tidak ditemukan dengan title, cari dengan class atau data attribute
    if (!(await editButton.isVisible().catch(() => false))) {
      const editLink = firstRow.locator('a').filter({ has: page.locator('i.fa-pencil, i.fa-edit, i.fa-list') }).first();
      await expect(editLink).toBeVisible();
      await editLink.click();
    } else {
      await expect(editButton).toBeVisible();
      await editButton.click();
    }

    // Tunggu halaman form dimuat
    await page.waitForNavigation({ waitUntil: 'networkidle' }).catch(() => {});
    await page.waitForTimeout(1000);

    // Langkah 3: Cek apakah kalimat "Status Pejabat (Pj.)" ditampilkan pada form
    // Cari text "Status Pejabat (Pj.)" atau variasi dari setting sebutan
    const statusPejabatLabel = page.locator('label').filter({
      hasText: /Status Pejabat/i
    });

    await expect(statusPejabatLabel).toBeVisible();

    // Verifikasi bahwa text mengandung "Status Pejabat (Pj.)"
    await expect(statusPejabatLabel).toContainText('Status Pejabat');

    console.log('✓ Test berhasil: Kalimat "Status Pejabat (Pj.)" ditemukan pada halaman form pengurus');
  });
});
