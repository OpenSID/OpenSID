import { test, expect} from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Detail penerima bantuan #9509', () => {
  test('fix: perbaiki detail penerima bantuan', {
    annotation: {
        type: 'issue',
        description: 'https://github.com/OpenSID/OpenSID/issues/9509'
    }
  }, async ({ page }) => {
    await page.goto('program_bantuan');

    await expect(page.getByRole('gridcell', { name: 'BLT DD' })).toBeVisible();
    await page.getByRole('link', { name: '' }).first().click();
    await expect(page.getByRole('gridcell', { name: 'NURKIAH' }).first()).toBeVisible();
    await page.getByRole('gridcell', { name: '1505024101600008' }).first().click();
    await expect(page.getByRole('cell', { name: 'NURKIAH - 1505024101600008' })).toBeVisible();
  });
});