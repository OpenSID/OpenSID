import { test, expect} from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: BAGAN STRUKTUR ORGANISASI PEMERINTAH DESA TIDAK TAMPIL #10971', () => {
  test('fix: perbaiki bagan struktur organisasi pemerintah desa tidak tampil', {
    annotation: {
        type: 'issue',
        description: 'https://github.com/OpenSID/OpenSID/issues/10971'
    }
  }, async ({ page }) => {    
    await page.goto('pengurus');
    await page.getByRole('link', { name: '' }).nth(1).click();
    await page.locator('select[name=atasan]').selectOption({index: 1});
    await page.getByRole('button', { name: ' Simpan' }).click();
    
    await page.goto('pengurus/bagan/bpd');
    await expect(page.locator('.highcharts-background')).toBeVisible();
  });
});