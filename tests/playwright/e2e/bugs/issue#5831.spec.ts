import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: DOM-Based XSS pada Fitur QR Scanner (HTML / SVG Injection) #5831', () => {
  test('fix: perbaiki DOM-Based XSS pada Fitur QR Scanner (HTML / SVG Injection)', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/#5831',
    },
  }, async ({ page }) => {
    await page.goto('qrcode');
    await page.getByRole('textbox', { name: 'Isi Kode : Kolom ini' }).click();
    await page.getByRole('textbox', { name: 'Isi Kode : Kolom ini' }).fill('<script>alert(\'INLINE_JS_EXEC\')</script>');
    await page.getByRole('button', { name: ' Buat' }).click();
    await expect(page.getByRole('heading', { name: 'Validasi Gagal' })).toBeVisible();
    await expect(page.getByText('Isi Kode mengandung karakter')).toBeVisible();
  });
});
