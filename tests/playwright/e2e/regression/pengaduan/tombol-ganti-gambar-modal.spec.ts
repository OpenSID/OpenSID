// Issue: #10724 — Tombol ganti gambar di modal pengajuan isi kurva pengaduan
// https://github.com/OpenSID/OpenSID/issues/10724
import { test, expect } from '../../../utils/regressionTest';


test.describe('Bug/error: Tombol Ganti Gambar di Modals Pengajuan isi kurva "[ ]" #10724', () => {
  test('fix: Tombol Ganti Gambar di Modals Pengajuan isi kurva "[ ]"', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10724',
    },
  }, async ({ page }) => {
    // 1. Akses halaman Pengaduan (publik).
    await page.goto('pengaduan');

    // 2. Buka modal pengajuan lewat tombol pemicunya yang spesifik. Tema aktif
    //    (esensi) Bootstrap 5 memakai `data-bs-target`; sertakan `data-target`
    //    sebagai cadangan untuk tema Bootstrap 3 (lestari). Selector lama
    //    `[data-toggle=modal]` terlalu lebar → mengklik elemen lain.
    await page.locator(
      'button[data-bs-target="#newpengaduan"], button[data-target="#newpengaduan"]'
    ).click();

    // 3. Modal muncul.
    const modal = page.locator('#newpengaduan');
    await expect(modal).toBeVisible({ timeout: 15000 });

    // 4. Regresi #10724: teks literal "[Ganti Gambar]" sudah TIDAK ada lagi.
    await expect(modal.getByText(/\[\s*Ganti Gambar\s*\]/i)).toHaveCount(0);

    // 5. Pemicu ganti gambar (#10724): tombol ikon refresh `title="Ganti Gambar"`
    //    (esensi) atau klik gambar `#b-captcha` (lestari). Pastikan src berubah.
    const captchaImg = modal.locator('#captcha');
    await expect(captchaImg).toBeVisible({ timeout: 15000 });
    const oldSrc = await captchaImg.getAttribute('src');

    await modal.locator('button[title="Ganti Gambar"], #b-captcha').first().click();

    await expect(async () => {
      expect(await captchaImg.getAttribute('src')).not.toBe(oldSrc);
    }).toPass({ timeout: 5000 });
  });
});
