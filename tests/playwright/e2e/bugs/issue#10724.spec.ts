import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Tombol Ganti Gambar di Modals Pengajuan isi kurva "[ ]" #10724', () => {
  test('fix: Tombol Ganti Gambar di Modals Pengajuan isi kurva "[ ]"', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10724',
    },
  }, async ({ page }) => {
    // 1. Akses halaman Pengaduan
    await page.goto('pengaduan');

    // 2. Klik tombol untuk memanggil modal dengan ID newpengaduan
    // Sesuaikan selector tombol pemicunya, biasanya tombol "Tambah Pengaduan"
    await page.click('[data-target="#newpengaduan"], [data-toggle="modal"]');

    // 3. Pastikan modal muncul dan terlihat
    const modal = page.locator('#newpengaduan');
    await expect(modal).toBeVisible();

    // 4. CEK: Pastikan teks "[Ganti Gambar]" sudah TIDAK ADA lagi (Sesuai Issue #10724)
    // Kita menggunakan regex agar pencarian lebih akurat
    const gantiGambarText = page.locator('text=/\[\s*Ganti Gambar\s*\]/i');
    await expect(gantiGambarText).not.toBeVisible();

    // 5. CEK: Pastikan tombol refresh (ikon) baru sudah ada
    // Jika menggunakan ikon Bootstrap 3 (Glyphicon) atau SVG yang kita buat tadi
    const refreshButton = modal.locator('button[title="Ganti Gambar"], .glyphicon-refresh');
    await expect(refreshButton).toBeVisible();

    // 6. OPSIONAL: Uji fungsi refresh (memastikan URL gambar berubah)
    const captchaImg = modal.locator('#captcha');
    const oldSrc = await captchaImg.getAttribute('src');
    
    await refreshButton.click();
    
    // Tunggu sebentar agar src berubah
    await page.waitForTimeout(500); 
    const newSrc = await captchaImg.getAttribute('src');
    
    // Pastikan src lama tidak sama dengan src baru (berhasil refresh)
    expect(oldSrc).not.toBe(newSrc);

  });
});
