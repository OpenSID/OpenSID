import { test, expect } from '@playwright/test';

test.describe('Issue #11232: Fix Tooltip pada tombol icon fa-eye', () => {
  test('Tooltip pada tombol icon fa-eye di kolom Aksi Layanan Mandiri Dokumen seharusnya "Lihat"', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11232',
    },
  }, async ({ page }) => {
    // 1. Pergi ke halaman login Layanan Mandiri
    await page.goto('layanan-mandiri/masuk');

    try {
      await page.getByText('Terima semua cookie', { exact: true }).click();
    } catch { } // abaikan jika tidak ada pop-up cookie

    // 2. Login menggunakan NIK dan PIN user test yang sudah diseed
    await page.getByRole('textbox', { name: 'NIK' }).fill('1505022111940001');
    await page.getByRole('textbox', { name: 'PIN' }).fill('123456');
    await page.getByRole('button', { name: 'MASUK', exact: true }).click();

    // Tunggu redirect setelah login berhasil
    await page.waitForNavigation({ waitUntil: 'networkidle' });

    // 3. Pergi ke halaman dokumen Layanan Mandiri
    await page.goto('layanan-mandiri/dokumen');
    await page.waitForLoadState('networkidle');

    // 4. Pastikan tabel dokumen telah selesai dimuat
    await page.waitForSelector('table#tabeldata', { timeout: 10000 });

    // 5. Temukan tombol dengan icon fa-eye di dalam tabel
    // Berdasarkan class pada controller: btn bg-purple btn-sm
    const eyeButton = page.locator('table#tabeldata tbody tr').first().locator('a.bg-purple');
    
    // Tunggu sampai tombol terlihat (jika ada dokumen di dalam tabel)
    if (await eyeButton.count() > 0) {
      await expect(eyeButton).toBeVisible();

      // 6. Verifikasi tooltip (title) dari tombol tersebut adalah "Lihat"
      const titleAttribute = await eyeButton.getAttribute('title');
      expect(titleAttribute).toBe('Lihat');
    }
  });
});
