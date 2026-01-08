import { test, expect } from '@playwright/test';

test.describe('Bug/error: Melanjutkan Issue Error Buku Tamu https://github.com/OpenSID/OpenSID/issues/10674 #10698', () => {
  test('fix: perbaiki izin kamera tidak menampilkan popup error di halaman buku-tamu', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10698',
    },
  }, async ({ page }) => {
    // Navigasi ke halaman buku-tamu
    await page.goto('buku-tamu');

    // Tunggu beberapa detik untuk memastikan semua script sudah berjalan
    await page.waitForTimeout(2000);

    // Periksa bahwa halaman berisi form registrasi
    await expect(page.getByRole('heading', { name: 'Register Tamu' })).toBeVisible();

    // Periksa bahwa tidak ada error dialog Swal.fire dengan title "Permintaan Akses Kamera"
    const errorDialog = page.locator('.swal2-title');
    
    // Jika ada dialog, pastikan itu bukan error kamera
    if (await errorDialog.isVisible()) {
      const errorTitle = await errorDialog.textContent();
      expect(errorTitle).not.toContain('Permintaan Akses Kamera');
    }

    // Periksa form input ada dan terlihat
    await expect(page.locator('input[name="nama"]')).toBeVisible();
    await expect(page.locator('input[name="instansi"]')).toBeVisible();
    await expect(page.locator('select[name="jenis_kelamin"]')).toBeVisible();
    await expect(page.locator('select[name="id_bidang"]')).toBeVisible();
  });
});
