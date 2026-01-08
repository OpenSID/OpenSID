import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Tampilan Previews Cetak Pada Halaman Buku Administrasi Umum - Buku Tanah Kas Desa Terpotong Setengah dan Nama Penandatangan tidak muncul #10685', () => {
  test('fix: perbaiki dan refaktor preview cetak/unduh pada Buku Tanah Kas desa', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/#10686',
    },
  }, async ({ page }) => {
    // 1. Akses halaman Buku Tanah Kas Desa
    await page.goto('bumindes_tanah_kas_desa');

    // 2. Pastikan halaman sudah loading dengan benar
    await expect(page.locator('text=Buku Tanah Kas').first()).toBeVisible();

    // 3. Klik tombol Cetak/Unduh
    await page.getByText('Cetak/Unduh', { exact: true }).click();
    await page.getByRole('link', { name: 'Cetak' }).click();

    // 4. Tunggu dialog muncul dan pastikan ada input tanggal cetak
    await expect(page.locator('#modalBox')).toBeVisible();

    // 5. Isi tanggal cetak jika diperlukan
    const tglCetakInput = page.locator('input[name="tgl_cetak"]');
    if (await tglCetakInput.isVisible()) {
      await tglCetakInput.fill(new Date().toISOString().split('T')[0]);
    }

    // 6. Tunggu popup cetak muncul
    const page2Promise = page.waitForEvent('popup');
    await page.locator('#form-cetak').getByText('Cetak', { exact: true }).click();
    const page2 = await page2Promise;

    // 7. Verifikasi bahwa preview cetak muncul
    await page2.waitForLoadState('networkidle');

    // 8. Cek bahwa iframe cetak sudah dimuat
    const printFrame = page2.locator('iframe[name="print-frame"]').first();
    await expect(printFrame).toBeVisible();

    // 9. Verifikasi judul dokumen "BUKU TANAH DESA BULAN"
    const frameContent = printFrame.contentFrame();
    await expect(frameContent.locator('text=BUKU TANAH DESA BULAN')).toBeVisible();

    // 10. Verifikasi bahwa table header ditampilkan lengkap
    await expect(frameContent.locator('text=NOMOR URUT')).toBeVisible();
    await expect(frameContent.locator('text=ASAL TANAH KAS DESA')).toBeVisible();
    await expect(frameContent.locator('text=NOMOR SERTIFIKAT BUKU LETTER C / PERSIL')).toBeVisible();

    // 11. Verifikasi bahwa table ditampilkan lengkap dengan scroll horizontal
    // Cek header table ada (tidak terpotong)
    await expect(frameContent.locator('text=HUTAN').first()).toBeVisible({ timeout: 10000 });

    // 12. Verifikasi preview modal memiliki lebar landscape (1122px)
    const printModal = page2.locator('#print-modal');
    const modalWidth = await printModal.evaluate(el => el.offsetWidth);
    expect(modalWidth).toBeGreaterThan(1000); // Landscape width

    // 13. Verifikasi tombol cetak dan close ada di control panel
    await expect(page2.locator('#print-modal-controls a.print')).toBeVisible();
    await expect(page2.locator('#print-modal-controls a.close')).toBeVisible();

    // 14. Tutup popup cetak
    await page2.locator('#print-modal-controls a.close').click();
    await page2.waitForLoadState();

    // 15. Kembali ke halaman utama dan verifikasi masih bisa cetak ulang
    await expect(page.locator('text=Buku Tanah Kas').first()).toBeVisible();
  });
});
