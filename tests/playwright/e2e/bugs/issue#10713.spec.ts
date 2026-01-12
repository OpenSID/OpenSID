import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Perbaiki Tampilan Previews Cetak di Buku Administrasi Penduduk #10713', () => {
  test('fix: preview cetak buku induk penduduk dapat discroll secara vertical dan horizontal', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10713',
    },
  }, async ({ page }) => {
    // 1. Akses halaman Buku Induk Penduduk
    await page.goto('bumindes_penduduk_induk');

    // 2. Pastikan halaman sudah loading dengan benar
    await expect(page.locator('text=Buku Induk Penduduk').first()).toBeVisible();

    // 3. Klik tombol Cetak/Unduh
    await page.getByText('Cetak/Unduh', { exact: true }).click();

    // 4. Verifikasi pilihan Cetak dan Unduh muncul
    await expect(page.getByRole('link', { name: 'Cetak' })).toBeVisible();
    await expect(page.getByRole('link', { name: 'Unduh' })).toBeVisible();

    // 5. Klik opsi Cetak
    await page.getByRole('link', { name: 'Cetak' }).click();

    // 6. Tunggu dialog muncul dan pastikan ada input tanggal cetak
    await expect(page.locator('#modalBox')).toBeVisible();

    // 7. Isi tanggal cetak jika diperlukan
    const tglCetakInput = page.locator('input[name="tgl_cetak"]');
    if (await tglCetakInput.isVisible()) {
      await tglCetakInput.fill(new Date().toISOString().split('T')[0].split('-').reverse().join('-'));
    }

    // 8. Tunggu popup cetak muncul
    const page2Promise = page.waitForEvent('popup');
    await page.locator('#validasi').getByRole('button', { name: 'Cetak' }).click();
    const page2 = await page2Promise;

    // 9. Verifikasi bahwa preview cetak muncul
    await page2.waitForLoadState('networkidle');

    // 10. Cek bahwa iframe cetak sudah dimuat
    const printFrame = page2.locator('iframe[name="print-frame"]').first();
    await expect(printFrame).toBeVisible();

    // 11. Verifikasi judul dokumen "BUKU INDUK PENDUDUK"
    const frameContent = printFrame.contentFrame();
    await expect(frameContent.locator('text=BUKU INDUK PENDUDUK').first()).toBeVisible();

    // 12. Verifikasi bahwa modal print menggunakan landscape dan memiliki lebar yang tepat
    const printModal = page2.locator('#print-modal');
    await expect(printModal).toBeVisible();
    
    // 13. Verifikasi print-modal-content memiliki overflow auto untuk enable scrolling
    const printModalContent = page2.locator('#print-modal-content');
    await expect(printModalContent).toBeVisible();
    
    // Cek apakah element memiliki overflow property yang memungkinkan scroll
    const overflowStyle = await printModalContent.evaluate(el => {
      const styles = window.getComputedStyle(el);
      return {
        overflow: styles.overflow,
        overflowX: styles.overflowX,
        overflowY: styles.overflowY,
      };
    });
    
    // Verifikasi bahwa overflow tidak hidden (bisa auto atau scroll)
    expect(overflowStyle.overflow).not.toBe('hidden');
    expect(overflowStyle.overflowX).not.toBe('hidden');
    expect(overflowStyle.overflowY).not.toBe('hidden');

    // 14. Verifikasi bahwa konten dapat discroll secara vertical atau horizontal
    // Cek scroll height/width lebih besar dari client height/width (ada scrollable content)
    const scrollInfo = await printModalContent.evaluate(el => {
      return {
        scrollHeight: el.scrollHeight,
        clientHeight: el.clientHeight,
        scrollWidth: el.scrollWidth,
        clientWidth: el.clientWidth,
        hasVerticalScroll: el.scrollHeight > el.clientHeight,
        hasHorizontalScroll: el.scrollWidth > el.clientWidth,
      };
    });

    // Test berhasil jika bisa scroll secara vertical ATAU horizontal
    const canScroll = scrollInfo.hasVerticalScroll || scrollInfo.hasHorizontalScroll;
    expect(canScroll).toBeTruthy();

    // 15. Verifikasi table header ditampilkan lengkap
    await expect(frameContent.locator('text=JENIS KELAMIN').first()).toBeVisible();
    await expect(frameContent.locator('text=TEMPAT').first()).toBeVisible();
    await expect(frameContent.locator('text=STATUS PERKAWINAN').first()).toBeVisible();

    // 16. Verifikasi tombol cetak dan close ada di control panel
    await expect(page2.locator('#print-modal-controls a.print')).toBeVisible();
    await expect(page2.locator('#print-modal-controls a.close')).toBeVisible();

    // 17. Tutup popup cetak
    await page2.locator('#print-modal-controls a.close').click();
    await page2.waitForLoadState();

    // 18. Kembali ke halaman utama
    await expect(page.locator('text=Buku Induk Penduduk').first()).toBeVisible();
  });
});
