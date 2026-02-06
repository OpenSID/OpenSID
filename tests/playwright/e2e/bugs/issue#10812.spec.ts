import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Perbaiki Fungsi Pencarian marga agar yang disorot sesuai dengan marga yang dicari #10812', () => {
  test('fix: perbaikan select marga form penduduk', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10812',
    },
  }, async ({ page }) => {
    // Buka halaman form penduduk
    await page.goto('penduduk/form/99');
    await page.waitForLoadState('networkidle');
    
    // Buka dropdown marga
    await page.locator('#marga').click();
    await page.waitForSelector('.select2-search__field', { state: 'visible' });
    
    // Ketik pencarian
    await page.locator('.select2-search__field').fill('AA');
    await page.waitForTimeout(500);
    
    // Verifikasi item pertama ter-highlight
    const highlighted = page.locator('.select2-results__option--highlighted').first();
    await expect(highlighted).toBeVisible();
    await expect(highlighted).toHaveAttribute('aria-selected', 'true');
    
    // Verifikasi text sesuai pencarian
    const text = await highlighted.textContent();
    expect(text?.toLowerCase()).toContain('aa');
    
    // Tekan Enter untuk select
    await page.keyboard.press('Enter');
    
    // Verifikasi terpilih
    const value = await page.locator('#marga').inputValue();
    expect(value.toLowerCase()).toContain('aa');
  });
});