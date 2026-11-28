// Fitur: Opsi "Arsip Surat" (surat sintetis vs placeholder) pada Acak Data.
//
// Acak Data me-render ulang arsip surat dari template + entri form yang sudah
// disanitasi (mode "sintetis", default) atau menggantinya dengan PDF placeholder
// kosong (mode "placeholder").
//
// CATATAN: spec ini sengaja TIDAK menjalankan proses Acak — proses itu
// menganonimkan SELURUH database opensid_playwright (tidak dapat dipulihkan oleh
// beforeEach) dan akan merusak semua test lain di suite. Verifikasi end-to-end
// pembuatan PDF sintetis hanya bisa dilakukan pada database sekali-pakai. Di sini
// kita memverifikasi opsi UI ter-render dan ter-wire dengan benar (super admin,
// route, blade, dan nilai default).
import { test, expect } from '../../../utils/regressionTest';

test.describe('Acak Data — opsi Arsip Surat (surat sintetis)', () => {
  test('tab Acak menampilkan opsi Arsip Surat dengan "Surat sintetis" terpilih default', async ({ page }) => {
    await page.goto('acak-data');

    // Halaman Acak (tab 3) terbuka untuk super admin — bukan 403.
    await expect(page.locator('#maincontent')).toContainText('Arsip Surat');

    const sintetis = page.locator('input[name="surat_mode"][value="sintetis"]');
    const placeholder = page.locator('input[name="surat_mode"][value="placeholder"]');

    // Kedua pilihan tersedia.
    await expect(sintetis).toHaveCount(1);
    await expect(placeholder).toHaveCount(1);

    // Default: surat sintetis terpilih, placeholder tidak.
    await expect(sintetis).toBeChecked();
    await expect(placeholder).not.toBeChecked();

    // Teks penjelas tersedia.
    await expect(page.locator('#maincontent')).toContainText('di-render ulang dari template');
    await expect(page.locator('#maincontent')).toContainText('ISI SURAT DIREDAKSI');
  });

  test('pilihan placeholder dapat dipilih (radio mutually exclusive)', async ({ page }) => {
    await page.goto('acak-data');

    const sintetis = page.locator('input[name="surat_mode"][value="sintetis"]');
    const placeholder = page.locator('input[name="surat_mode"][value="placeholder"]');

    await placeholder.check();

    await expect(placeholder).toBeChecked();
    await expect(sintetis).not.toBeChecked();
  });
});
