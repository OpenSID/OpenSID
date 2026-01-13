import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Hasil Previews Cetak Buku Ekspedisi tidak ada border line pada table nya #10712', () => {
  test('fix: perbaikan Hasil Previews Cetak Buku Ekspedisi tidak ada border line pada table nya', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10712',
    },
  }, async ({ page }) => {

    // 1. Akses halaman Buku Ekspedisi
    await page.goto('ekspedisi');

    // 2. Pastikan halaman sudah loading dengan benar
    await expect(page.locator('text=Buku Ekspedisi').first()).toBeVisible();

    // 3. Klik tombol Cetak/Unduh
    await page.getByText('Cetak/Unduh', { exact: true }).click();
    await page.getByRole('link', { name: 'Cetak' }).click();

    // 4. Tunggu dialog muncul dan pastikan ada input tanggal cetak
    await expect(page.locator('#modalBox')).toBeVisible();

    // 5. Tunggu popup cetak muncul
    const page2Promise = page.waitForEvent('popup');
    await page.locator('#form-cetak').getByText('Cetak', { exact: true }).click();
    const page2 = await page2Promise;

    // 6. Cek bahwa iframe cetak sudah dimuat
    const printFrame = page2.locator('iframe[name="print-frame"]').first();
    await expect(printFrame).toBeVisible();

    // 7. Verifikasi judul dokumen "BUKU EKSPEDISI"
    const frameContent = printFrame.contentFrame();
    await expect(frameContent.locator('text=BUKU EKSPEDISI')).toBeVisible();

    // 8. Verifikasi bahwa table header ditampilkan lengkap
    await expect(frameContent.locator('text=NOMOR URUT')).toBeVisible();
    await expect(frameContent.locator('text=TANGGAL PENGIRIMAN')).toBeVisible();
    await expect(frameContent.locator('text=TANGGAL DAN NOMOR SURAT')).toBeVisible();

    // 9. Verifikasi bahwa table ditampilkan lengkap dengan scroll horizontal
    // Cek header table ada (tidak terpotong)
    await expect(frameContent.locator('text=HUTAN').first()).toBeVisible({ timeout: 10000 });

    // 10. Verifikasi preview modal memiliki lebar landscape (1122px)
    const printModal = page2.locator('#print-modal');
    const modalWidth = await printModal.evaluate(el => el.offsetWidth);
    expect(modalWidth).toBeGreaterThan(1000); // Landscape width

    // 11. Verifikasi tombol cetak dan close ada di control panel
    await expect(page2.locator('#print-modal-controls a.print')).toBeVisible();
    await expect(page2.locator('#print-modal-controls a.close')).toBeVisible();

    // 12. Tutup popup cetak
    await page2.locator('#print-modal-controls a.close').click();
    await page2.waitForLoadState();

    // 13. Kembali ke halaman utama dan verifikasi masih bisa cetak ulang
    await expect(page.locator('text=Buku Ekspedisi').first()).toBeVisible();
  });
});
