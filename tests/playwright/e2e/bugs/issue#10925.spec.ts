import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Gagal upload pdf pada artikel #10925', () => {
  test('fix: perbaikan Gagal upload pdf pada artikel', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10925',
    },
  }, async ({ page }) => {
    await page.goto('web/form');

    // Tunggu editor TinyMCE termuat
    await page.waitForSelector('.tox-tinymce', { timeout: 10000 });

    // Klik tombol upload file di TinyMCE
    await page.frameLocator('.tox-edit-area__iframe')
      .locator('body')
      .click();

    // Klik tombol Insert > Media atau toolbar upload
    await page.click('button[title="Insert file"]').catch(() =>
      page.click('.tox-tbtn[aria-label="Insert file"]')
    );

    // Tunggu dialog file manager muncul
    await page.waitForSelector('iframe[id*="mce"]', { timeout: 5000 });

    // Siapkan file PDF untuk diupload
    const pdfPath = path.resolve(__dirname, '../../fixtures/sample.pdf');

    // Upload file PDF
    const [fileChooser] = await Promise.all([
      page.waitForEvent('filechooser'),
      page.click('input[type="file"]').catch(() =>
        page.click('button:has-text("Upload")')
      ),
    ]);

    await fileChooser.setFiles(pdfPath);

    // Pastikan tidak muncul pesan error "File is dangerous"
    await expect(
      page.locator('text=File is dangerous')
    ).not.toBeVisible({ timeout: 5000 });

    // Pastikan tidak muncul pesan error "Filetype not allowed"
    await expect(
      page.locator('text=Filetype not allowed')
    ).not.toBeVisible({ timeout: 5000 });

    // Pastikan file berhasil diupload (nama file muncul)
    await expect(
      page.locator('text=sample.pdf')
    ).toBeVisible({ timeout: 10000 });
  });
});
