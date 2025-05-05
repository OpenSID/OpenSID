import { test, expect} from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Bagan tanpa BPD tidak menampilkan chart #9491', () => {
  test.beforeEach(async ({ page }) => {
    test.setTimeout(180000);    
  });  
      
  test('bagan tanpa bpd tidak tampil', {
    annotation: {
        type: 'issue',
        description: 'https://github.com/OpenSID/OpenSID/issues/9491'
    }
  }, async ({ page }) => {    
    // set tanpa atasan
    await page.goto('pengurus');
    await page.getByRole('link', { name: '' }).first().click();
    await page.locator('select[name=atasan]').selectOption({index: 0});    
    await page.getByRole('button', { name: ' Simpan' }).click();
    await page.getByRole('link', { name: '' }).nth(1).click();
    await page.locator('select[name=atasan]').selectOption({index: 0});
    await page.getByRole('button', { name: ' Simpan' }).click();

    await page.goto('pengurus/bagan');
    await expect(page.getByText('Perhatian! Data Struktur')).toBeVisible();
  });
  
  test('bagan tanpa bpd tampil', {
    annotation: {
        type: 'issue',
        description: 'https://github.com/OpenSID/OpenSID/issues/9491'
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