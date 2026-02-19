import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Analisis Kerentanan Blind SSRF — OpenSID Premium #5974', () => {
  test('fix: perbaikan validasi URL pada form tambah galeri', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/premium/issues/5974',
    },
  }, async ({ page }) => {
    await page.goto('gallery/form/eyJpdiI6IktmTFJsMlI2Zit5VVlhM2JzNkR6L1E9PSIsInZhbHVlIjoiVnZWQkgrOEN2by9GNW5BNCt6SmszUT09IiwibWFjIjoiYjkxNjljOTkwYzAzMGY4Njk5ZmYxMTRkZjU0NDE1YThjZjk4ZTIyMDg5YTRkOWZkMDFiNmIwYTMzYjEyMDkyMiIsInRhZyI6IiJ9');
    await page.locator('input[name="nama"]').fill('foto 1');
    await page.locator('#jenis').selectOption('2');
    await page.getByRole('textbox', { name: 'Link/URL' }).fill(
      'https://drive.google.com/file/d/1HdrQiVDy2vQeD7wv1-Zp9gpMcGhtMqXG/view1'
    );
    await page.getByRole('button', { name: ' Simpan' }).click();
    await expect(page.locator('.alert.alert-success.alert-dismissible p')).toContainText('Berhasil menambah data');
  });

  test('validasi: protokol tidak diizinkan', async ({ page }) => {
    await page.goto('gallery/form/eyJpdiI6IktmTFJsMlI2Zit5VVlhM2JzNkR6L1E9PSIsInZhbHVlIjoiVnZWQkgrOEN2by9GNW5BNCt6SmszUT09IiwibWFjIjoiYjkxNjljOTkwYzAzMGY4Njk5ZmYxMTRkZjU0NDE1YThjZjk4ZTIyMDg5YTRkOWZkMDFiNmIwYTMzYjEyMDkyMiIsInRhZyI6IiJ9');
    await page.locator('input[name="nama"]').fill('foto ftp');
    await page.locator('#jenis').selectOption('2');
    await page.getByRole('textbox', { name: 'Link/URL' }).fill('ftp://example.com/image.jpg');
    await page.getByRole('button', { name: ' Simpan' }).click();
    await expect(page.locator('.alert.alert-danger.alert-dismissible p')).toContainText('Hanya URL HTTP/HTTPS yang diizinkan.');
  });

  test('validasi: domain tidak di whitelist', async ({ page }) => {
    await page.goto('gallery/form/eyJpdiI6IktmTFJsMlI2Zit5VVlhM2JzNkR6L1E9PSIsInZhbHVlIjoiVnZWQkgrOEN2by9GNW5BNCt6SmszUT09IiwibWFjIjoiYjkxNjljOTkwYzAzMGY4Njk5ZmYxMTRkZjU0NDE1YThjZjk4ZTIyMDg5YTRkOWZkMDFiNmIwYTMzYjEyMDkyMiIsInRhZyI6IiJ9');
    await page.locator('input[name="nama"]').fill('foto evil');
    await page.locator('#jenis').selectOption('2');
    await page.getByRole('textbox', { name: 'Link/URL' }).fill('https://evil.com/image.jpg');
    await page.getByRole('button', { name: ' Simpan' }).click();
    await expect(page.locator('.alert.alert-danger.alert-dismissible p')).toContainText('Domain');
    await expect(page.locator('.alert.alert-danger.alert-dismissible p')).toContainText('tidak diizinkan');
  });

  test('validasi: domain lokal (.local)', async ({ page }) => {
    await page.goto('gallery/form/eyJpdiI6IktmTFJsMlI2Zit5VVlhM2JzNkR6L1E9PSIsInZhbHVlIjoiVnZWQkgrOEN2by9GNW5BNCt6SmszUT09IiwibWFjIjoiYjkxNjljOTkwYzAzMGY4Njk5ZmYxMTRkZjU0NDE1YThjZjk4ZTIyMDg5YTRkOWZkMDFiNmIwYTMzYjEyMDkyMiIsInRhZyI6IiJ9');
    await page.locator('input[name="nama"]').fill('foto .local');
    await page.locator('#jenis').selectOption('2');
    await page.getByRole('textbox', { name: 'Link/URL' }).fill('http://myapp.local/image.jpg');
    await page.getByRole('button', { name: ' Simpan' }).click();
    await expect(page.locator('.alert.alert-danger.alert-dismissible p')).toContainText('Domain lokal tidak diizinkan.');
  });
});
