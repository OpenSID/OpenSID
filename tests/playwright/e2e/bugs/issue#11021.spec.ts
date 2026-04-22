import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: filter pada menu statistik kependudukan tidak sesuai #11021', () => {
  test('fix: perbaiki filter pada menu statistik kependudukan tidak sesuai', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11021',
    },
  }, async ({ page }) => {
    await page.goto('/statistik/kependudukan');
    await page.waitForLoadState('networkidle');

    // Test Case 1: Filter Statistik Penduduk dengan Tahun 2026
    await page.selectOption('select[name="tahun"]', '2026');
    await page.click('button:has-text("Cari")');
    await page.waitForLoadState('networkidle');

    // Verify hasil tidak 0 untuk Pekerjaan (case statistik dasar)
    const pekerjaanCheckbox = page.locator('label:has-text("Pekerjaan")');
    await pekerjaanCheckbox.click();
    await page.waitForLoadState('networkidle');
    
    const jumlahCell = page.locator('table tbody tr:first-child td:nth-child(2)');
    const jumlahText = await jumlahCell.textContent();
    expect(parseInt(jumlahText)).toBeGreaterThan(0);

    // Test Case 2: Filter Bantuan Penduduk dengan Tahun 2026
    await page.selectOption('select[name="kategori"]', 'bantuan');
    await page.waitForLoadState('networkidle');
    
    await page.selectOption('select[name="tahun"]', '2026');
    await page.click('button:has-text("Cari")');
    await page.waitForLoadState('networkidle');

    // Verify hasil bantuan penduduk tidak 0
    const bantuanCount = page.locator('table tbody tr:first-child td:nth-child(2)');
    const bantuanText = await bantuanCount.textContent();
    expect(parseInt(bantuanText)).toBeGreaterThan(0);

    // Test Case 3: Verifikasi tahun lain juga menampilkan hasil
    await page.selectOption('select[name="tahun"]', '2025');
    await page.click('button:has-text("Cari")');
    await page.waitForLoadState('networkidle');

    const tahun2025Count = page.locator('table tbody tr:first-child td:nth-child(2)');
    const tahun2025Text = await tahun2025Count.textContent();
    expect(parseInt(tahun2025Text)).toBeGreaterThanOrEqual(0);
  });
});
