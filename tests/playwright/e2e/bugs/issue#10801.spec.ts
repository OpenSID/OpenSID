import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug: Google reCAPTCHA Error "Missing required parameters: sitekey" #10801', () => {
  test('fix: field site_key dan secret_key harus tersembunyi jika google_recaptcha tidak aktif', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10801',
    },
  }, async ({ page }) => {
    await page.goto('setting');
    await page.waitForLoadState('networkidle');

    // Klik Select2 untuk google_recaptcha
    await page.locator('#select2-google_recaptcha-container').click();
    // Pilih "Tidak" (value 0)
    await page.getByRole('treeitem', { name: 'Tidak' }).click();

    // Pastikan field site_key dan secret_key tersembunyi
    const siteKeyForm = page.locator('#form_google_recaptcha_site_key');
    const secretKeyForm = page.locator('#form_google_recaptcha_secret_key');

    await expect(siteKeyForm).toBeHidden();
    await expect(secretKeyForm).toBeHidden();
  });

  test('fix: field site_key dan secret_key harus tampil dan required jika google_recaptcha aktif', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10801',
    },
  }, async ({ page }) => {
    await page.goto('setting');
    await page.waitForLoadState('networkidle');

    // Klik Select2 untuk google_recaptcha
    await page.locator('#select2-google_recaptcha-container').click();
    // Pilih "Ya" (value 1)
    await page.getByRole('treeitem', { name: 'Ya' }).click();

    // Pastikan field site_key dan secret_key tampil
    const siteKeyForm = page.locator('#form_google_recaptcha_site_key');
    const secretKeyForm = page.locator('#form_google_recaptcha_secret_key');

    await expect(siteKeyForm).toBeVisible();
    await expect(secretKeyForm).toBeVisible();
  });

  test('fix: toggle google_recaptcha harus mengubah visibility dan required secara dinamis', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10801',
    },
  }, async ({ page }) => {
    await page.goto('setting');
    await page.waitForLoadState('networkidle');

    const siteKeyForm = page.locator('#form_google_recaptcha_site_key');
    const secretKeyForm = page.locator('#form_google_recaptcha_secret_key');

    // Toggle ke aktif (Ya)
    await page.locator('#select2-google_recaptcha-container').click();
    await page.getByRole('treeitem', { name: 'Ya' }).click();
    
    await expect(siteKeyForm).toBeVisible();
    await expect(secretKeyForm).toBeVisible();

    // Toggle ke tidak aktif (Tidak)
    await page.locator('#select2-google_recaptcha-container').click();
    await page.getByRole('treeitem', { name: 'Tidak' }).click();
    
    await expect(siteKeyForm).toBeHidden();
    await expect(secretKeyForm).toBeHidden();
  });
});
