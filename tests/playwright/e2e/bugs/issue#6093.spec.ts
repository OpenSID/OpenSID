import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Stored XSS via Attribute Injection pada Modul Pembangunan (Halaman Publik) #6093', () => {
  test('fix: perbaikan Stored XSS via Attribute Injection pada Modul Pembangunan (Halaman Publik)', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/premium/issues/6093',
    },
  }, async ({ page }) => {

    const xssPayload = `100%" onmouseover="document.body.innerHTML='<center><h1>Security Audit</h1></center>';"`;

    // -------------------------------------------------------
    // STEP 1: Coba simpan payload XSS via form admin
    // -------------------------------------------------------
    await test.step('Coba input payload XSS pada field persentase', async () => {
      await page.goto('/pembangunan_dokumentasi/form-dokumentasi/1');

      // Pilih mode "Tulis Manual"
      await page.getByLabel('Tulis Manual').click();

      // Isi field persentase dengan payload XSS
      await page.fill('input[name="persentase"]', xssPayload);
      await page.fill('textarea[name="keterangan"]', 'Security Audit Test');

      await page.click('button[type="submit"]');
    });

    // -------------------------------------------------------
    // STEP 2: Verifikasi server menolak payload (validasi regex)
    // -------------------------------------------------------
    await test.step('Server harus menolak payload XSS', async () => {
      // Setelah submit, harus muncul pesan error — bukan success
      await expect(page.locator('.alert-danger, .alert-error')).toBeVisible();
      await expect(page.locator('.alert-success')).not.toBeVisible();
    });

    // -------------------------------------------------------
    // STEP 3: Buka halaman publik detail pembangunan
    // -------------------------------------------------------
    await test.step('Buka halaman publik detail pembangunan', async () => {
      await page.goto('/pembangunan/lanjutan-pembagunan-tambatan-perahu');
      await expect(page).toHaveURL(/pembangunan/);
    });

    // -------------------------------------------------------
    // STEP 4: Verifikasi tidak ada event handler berbahaya di DOM
    // -------------------------------------------------------
    await test.step('Tidak ada atribut event handler berbahaya di halaman publik', async () => {
      const dangerousAttributes = [
        'onmouseover',
        'onerror',
        'onclick',
        'onload',
        'onfocus',
        'onmouseout',
      ];

      for (const attr of dangerousAttributes) {
        const elements = page.locator(`[${attr}]`);
        await expect(elements).toHaveCount(0, {
          message: `Ditemukan atribut berbahaya "${attr}" di halaman publik`,
        });
      }
    });

    // -------------------------------------------------------
    // STEP 5: Verifikasi karakter berbahaya sudah di-encode di HTML
    // -------------------------------------------------------
    await test.step('Karakter " harus ter-encode sebagai &quot; di source HTML', async () => {
      const content = await page.content();

      // Payload mentah tidak boleh ada di source
      expect(content).not.toContain(`onmouseover="`);
      expect(content).not.toContain(`onerror="`);

      // Jika ada data persentase, harus sudah di-encode
      if (content.includes('100%')) {
        expect(content).not.toMatch(/100%"\s+on\w+=/);
      }
    });

    // -------------------------------------------------------
    // STEP 6: Verifikasi halaman publik tidak ter-deface
    // -------------------------------------------------------
    await test.step('Halaman publik tidak ter-deface oleh XSS', async () => {
      // Konten asli halaman harus tetap ada
      await expect(page.locator('body')).not.toContainText('Security Audit');

      // Struktur halaman harus tetap normal (ada navbar/header)
      await expect(page.locator('body')).toBeVisible();
    });

  });
});