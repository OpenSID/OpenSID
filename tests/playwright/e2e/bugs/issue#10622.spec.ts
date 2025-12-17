import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Form Validasi Email Desa di Identitas tidak berfungsi #10622', () => {
  test('fix: Form Validasi Email Desa di Identitas tidak berfungsi', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10622',
    },
  }, async ({ page }) => {
    await page.goto('identitas_desa/form');

    const emailInput = page.locator('#email_desa');
    const saveButton = page.getByRole('button', { name: 'Simpan' });
    const originalEmail = await emailInput.inputValue();

    // 1. Test dengan email yang tidak valid
    await emailInput.fill('bukan-email-valid');
    await saveButton.click();

    // Tunggu dan pastikan modal error muncul
    const errorModal = page.locator('.swal2-popup');
    await expect(errorModal).toBeVisible();
    await expect(errorModal.locator('#swal2-title')).toHaveText('Gagal Ubah Data');
    await expect(errorModal.locator('#swal2-html-container')).toHaveText('Alamat email tidak valid.');

    // Tutup modal error
    await page.locator('.swal2-confirm').click();
    await expect(errorModal).not.toBeVisible();

    // 2. Test dengan email yang valid
    const testEmail = 'test.valid@example.com';

    await emailInput.fill(testEmail);
    await saveButton.click();

    // Tunggu dan pastikan modal sukses muncul dan terjadi pengalihan halaman
    const successModal = page.locator('.swal2-popup');
    await expect(successModal).toBeVisible();
    await expect(successModal.locator('#swal2-title')).toHaveText('Berhasil Ubah Data');
    
    // Tunggu hingga navigasi selesai
    await page.waitForURL('**/identitas_desa');
    expect(page.url()).toContain('identitas_desa');

    // 3. Kembalikan ke email semula untuk menjaga konsistensi data
    await page.goto('identitas_desa/form');
    await emailInput.fill(originalEmail);
    await saveButton.click();

    // Tunggu pengalihan halaman setelah mengembalikan data
    await page.waitForURL('**/identitas_desa');
    expect(page.url()).toContain('identitas_desa');
  });
});
