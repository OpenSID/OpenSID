import { test, expect } from '../../../utils/workerTest';

test.describe('Pemeriksaan Footer Halaman Utama', () => {
  test('harus menampilkan teks setelah "© 2026 -"', async ({ page }) => {
    // Navigasi ke halaman utama
    await page.goto('/');

    // Mencari teks "© 2026 -" di halaman
    // Kita gunakan regex untuk memastikan ada karakter setelah tanda hubung tersebut
    // \s* matches any whitespace, \S+ matches one or more non-whitespace characters
    const footerPattern = /© 2026 -\s*\S+/;

    // Pastikan teks tersebut terlihat di halaman
    await expect(page.locator('body')).toContainText(footerPattern);

    // Opsional: Ambil teks lengkap untuk logging jika diperlukan dalam laporan
    const bodyText = await page.innerText('body');
    const match = bodyText.match(/© 2026 - (.*)/);
    if (match && match[1].trim().length > 0) {
      console.log('Footer found with content:', match[0]);
    } else {
      throw new Error('Teks setelah "© 2026 -" tidak ditemukan atau kosong.');
    }
  });
});
