import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Batasi Payload pencarian di Thema masih bisa menampung lebih dari 1000 karakter #10759', () => {
  test('fix: perbaikan pembatasan panjang input', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10759',
    },
  }, async ({ page }) => {
    await page.goto('/');
    await page.getByRole('textbox', { name: 'Cari...' }).click();
    await page.getByRole('textbox', { name: 'Cari...' }).fill('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore ');
    await page.getByRole('textbox', { name: 'Cari...' }).press('Enter');
    await page.goto('http://127.0.0.1:8000/index.php/?cari=Lorem%20ipsum%20dolor%20sit%20amet,%20consectetur%20adipiscing%20elit,%20sed%20do%20eiusmod%20tempor%20incididunt%20ut%20labore%20et%20dolore%20magna%20aliqua.%20Ut%20enim%20ad%20minim%20veniam,%20quis%20nostrud%20exercitation%20ullamco%20laboris%20nisi%20ut%20aliquip%20ex%20ea%20commodo%20consequat.%20Duis%20aute%20irure%20dolor%20in%20reprehenderit%20in%20voluptate%20velit%20esse%20cillum%20dolore%20eu%20fugiat%20nulla%20pariatur.%20Excepteur%20sint%20occaecat%20cupidatat%20non%20proident,%20sunt%20in%20culpa%20qui%20officia%20deserunt%20mollit%20anim%20id%20est%20laborum.');
    await expect(page.locator('div').filter({ hasText: 'Belum ada artikel yang' }).nth(2)).toBeVisible();
  });
});
