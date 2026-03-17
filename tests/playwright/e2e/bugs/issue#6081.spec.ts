import { test, expect } from '@playwright/test';
import path from 'path';
const fs = require('fs');
const os = require('os');
const XLSX = require('xlsx');

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: CSV/Formula Injection pada Ekspor Excel Data Terdata #6081', () => {
  test('fix: perbaikan CSV/Formula Injection pada Ekspor Excel Data Terdata', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/premium/issues/6081',
    },
  }, async ({ page }) => {
    await page.goto(`/suplemen/form_terdata`);

    // Pilih penduduk pertama yang tersedia
    await page.click('.select2-container');
    await page.waitForSelector('.select2-results__option', { timeout: 5_000 });
    await page.click('.select2-results__option:first-child');

    // Isi keterangan dengan payload injection
    await page.fill('textarea[name="keterangan"]', '=1+1');
    await page.click('button[type="submit"]');
    await expect(page.locator('.alert-success')).toBeVisible({ timeout: 8_000 });

    // Unduh file ekspor
    const [download] = await Promise.all([
        page.waitForEvent('download'),
        page.goto(`/suplemen/ekspor`),
    ]);
    const filePath = path.join(`suplemen.xlsx`);
    await download.saveAs(filePath);

    // 6. Baca file XLSX dan cek nilainya
    const workbook = XLSX.readFile(filePath);
    const sheet    = workbook.Sheets[workbook.SheetNames[0]];
    const rows     = XLSX.utils.sheet_to_json(sheet, { header: 1 });

    // Cari nilai keterangan di kolom F (index 5), lewati baris header
    const keteranganValues = rows.slice(1).map(row => String(row[5] ?? ''));

    // Tidak boleh ada nilai '2' (hasil eksekusi =1+1)
    expect(keteranganValues, 'Formula =1+1 tidak boleh dieksekusi menjadi 2').not.toContain('2');

    // Harus ada nilai yang diawali tanda petik tunggal sebagai escape
    const escaped = keteranganValues.some(val => val.startsWith("'="));
    expect(escaped, "Nilai '=1+1' harus di-escape menjadi \"'=1+1\" di file Excel").toBeTruthy();
  });
});
