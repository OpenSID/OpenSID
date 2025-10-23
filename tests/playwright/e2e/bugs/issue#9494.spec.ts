import { test, expect} from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Duplikasi Pengaturan Slider? #9494', () => {
  test('fix: perbaiki duplikasi pengaturan slider', {
    annotation: {
        type: 'issue',
        description: 'https://github.com/OpenSID/OpenSID/issues/9494'
    }
  }, async ({ page }) => {
    await page.goto('setting');
    await expect(page.locator('#form_sumber_gambar_slider')).not.toBeVisible();
  });
});