import { test, expect} from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../../storage/auth/admin.json'),
});

test.describe('Bug/error: Error ketika impor dari Google Form untuk analisis baru #9472', () => {
  test('fix: import analisis', {
    annotation: {
        type: 'issue',
        description: 'https://github.com/OpenSID/OpenSID/issues/9472'
    }
  }, async ({ page }) => {
    await page.goto('analisis_master');
    await page.getByText('Tambah Analisis Baru').click();
    await page.getByRole('link', { name: ' Impor dari Google Form' }).click();
    await page.locator('#input-form-id').click();
    await page.locator('#input-form-id').fill('1FAIpQLSdEXAMPLEID123456789');
    await page.getByText('Impor', { exact: true }).click();
    await page.goto('analisis_master');
  });
});