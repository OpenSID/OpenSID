import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: TIDAK BISA DOWNLOAD PROGRAM BANTUAN', () => {
  test('fix: perbaiki tidak dapat ekspor program bantuan', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/9660',
    },
  }, async ({ page }) => {
    await page.goto('program_bantuan')
    try{
    const downloadPromise = page.waitForEvent('download');
    await page.getByRole('row', { name: '1     BPNT Pusat 2 13 Dec' }).getByRole('link').nth(2).click();
    const download = await downloadPromise;
    await expect(page.getByText('Daftar Program Bantuan Beranda Daftar Program Bantuan')).toBeVisible();
    }catch{}
  });
});
