import { test, expect} from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Terjadi error saat cetak agenda surat masuk #9343', () => {
  test('fix: perbaiki cetak unduh', {
    annotation: {
        type: 'issue',
        description: 'https://github.com/OpenSID/OpenSID/issues/9343'
    }
  }, async ({ page }) => {
    await page.goto('surat_masuk');
    await page.getByRole('link', { name: 'Cetak' }).click();
    await expect(page.locator('#modalBox')).toContainText('Cetak Agenda Surat Masuk');
    const page1Promise = page.waitForEvent('popup');
    await page.locator('#form-cetak').getByText('Cetak', { exact: true }).click();
    const page1 = await page1Promise;
    await expect(page1.locator('iframe[name="print-frame"]').contentFrame().locator('span')).toContainText('AGENDA SURAT MASUK');
    await page1.getByRole('link', { name: 'Close' }).click();
    await page.getByRole('link', { name: 'Cetak' }).click();
    await expect(page.locator('#modalBox')).toContainText('Cetak Agenda Surat Masuk');
  });
});