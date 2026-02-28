import { test, expect} from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: pengaturan penomoran surat #9604', () => {
  test('fix: pengaturan penomoran surat', {
    annotation: {
        type: 'issue',
        description: 'https://github.com/OpenSID/OpenSID/issues/9604'
    }
  }, async ({ page }) => {
    await page.goto('setting');
    const bodyText = await page.locator('body').innerText();
    expect(bodyText.toLowerCase()).not.toContain('Penomoran Surat');
  });
});