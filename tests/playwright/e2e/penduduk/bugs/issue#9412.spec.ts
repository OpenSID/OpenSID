import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../../storage/auth/admin.json'),
});

test.describe('Inconsisten format penulisan pendidikan dalam KK #9412', () => {
  test('fix: perbaikan pendidikan kk', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/9412'
    }
  }, async ({ page }) => {
    await page.goto('penduduk');
    
    await page.getByLabel('Tampilkan 102550100Semua entri').selectOption('100');
    await page.getByRole('link', { name: '20', exact: true }).click();
    await page.getByRole('link', { name: '16', exact: true }).click();
    await page.getByRole('link', { name: '15', exact: true }).click();
    await page.getByRole('link', { name: '14', exact: true }).click();
    await expect(page.locator('tbody')).toContainText('TIDAK/BELUM SEKOLAH');
    await expect(page.locator('tbody')).toContainText('TAMAT SD/SEDERAJAT');
    await expect(page.locator('tbody')).toContainText('SLTA/SEDERAJAT');
    await expect(page.locator('tbody')).toContainText('DIPLOMA I/II');
    await expect(page.locator('tbody')).toContainText('AKADEMI/DIPLOMA III/S. MUDA');
    await expect(page.locator('tbody')).toContainText('DIPLOMA IV/STRATA I');


  });
});

