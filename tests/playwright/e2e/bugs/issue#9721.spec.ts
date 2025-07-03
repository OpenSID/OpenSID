import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Data penduduk ketika klik dari statistik Statistik Akta Kelahiran tidak tampil/kosong #9721', () => {
  test('fix: perbaiki data akta kelahiran tidak sesuai', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/9721',
    },
  }, async ({ page }) => {
    await page.goto('statistik/penduduk/17');
    await expect(page.locator('tbody')).toContainText('UMUR 0 S/D 4 TAHUN');
    await expect(page.locator('tbody')).toContainText('2');
    const page1Promise = page.waitForEvent('popup');
    await page.getByRole('link', { name: '2' }).first().click();
    const page1 = await page1Promise;
    await expect(page1.locator('#tabeldata_info')).toContainText('Menampilkan 1 sampai 2 dari 2 entri');
  });
});