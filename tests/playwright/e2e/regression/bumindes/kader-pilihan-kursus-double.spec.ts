// Issue: #10661 — Pilihan kursus dan bidang keahlian buku kader pemberdayaan bisa memilih double
// https://github.com/OpenSID/OpenSID/issues/10661
import { test, expect, Page } from '../../../utils/regressionTest';


test.describe('Bug/error: Pilihan Kursus dan Bidang Keahlian Buku Kader Pemberdayaan bisa memilih double #10661', () => {
  test('fix: perbaiki ilihan Kursus dan Bidang Keahlian Buku Kader Pemberdayaan bisa memilih double', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10661',
    },
  }, async ({ page }) => {
        // Bootstrap Tokenfield v1.x: #kursus dipindahkan ke dalam .tokenfield wrapper.
        // Input baru memiliki id "kursus-tokenfield" (original_id + "-tokenfield").
        // Token (nilai terpilih) dimasukkan sebelum input dengan selector "#kursus ~ .token".
        const getKursusInput = (page: Page) => page.locator('#kursus-tokenfield');
        const getKursusTokens = (page: Page) => page.locator('#kursus ~ .token');
        const getAutocompleteSuggestions = (page: Page) => page.locator('ul.ui-autocomplete li.ui-menu-item');

        await page.goto('/bumindes_kader/form');
        // Wait for tokenfield to initialise (never use networkidle on admin pages — background
        // polling blocks forever). #kursus-tokenfield is injected by Bootstrap Tokenfield.
        await page.waitForSelector('#kursus-tokenfield', { timeout: 15000 });

        const kursusInput = getKursusInput(page);
        const kursusTokens = getKursusTokens(page);

        // 1. Tambah kursus pertama: "Kursus Komputer"
        await kursusInput.fill('Komputer');
        await expect(getAutocompleteSuggestions(page)).toHaveCount(1, { timeout: 5000 });
        await getAutocompleteSuggestions(page).first().click();
        await expect(kursusTokens).toHaveCount(1, { timeout: 5000 });

        // 2. Tambah kursus kedua: "Kursus Bahasa Inggris" (kursus yang berbeda)
        await kursusInput.fill('Bahasa Inggris');
        await expect(getAutocompleteSuggestions(page)).toHaveCount(1, { timeout: 5000 });
        await getAutocompleteSuggestions(page).first().click();
        await expect(kursusTokens).toHaveCount(2, { timeout: 5000 });

        // 3. Bersihkan: hapus kedua token untuk verifikasi cleanup
        await kursusTokens.locator('.close').first().click();
        await expect(kursusTokens).toHaveCount(1);
        await kursusTokens.locator('.close').first().click();
        await expect(kursusTokens).toHaveCount(0);

  });
});
