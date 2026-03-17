import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Tombol Keterangan Tidak Merespon Saat Diklik pada Halaman Permohonan Surat Layanan Mandiri #10944', () => {
  test('fix: perbaikan Tombol Keterangan Tidak Merespon Saat Diklik pada Halaman Permohonan Surat Layanan Mandiri', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10944',
    },
  }, async ({ page }) => {
    // 1. Masuk sebagai penduduk dan buka halaman permohonan surat
    await page.goto('layanan-mandiri/permohonan-surat');

    // 2. Cari baris tabel yang berisi surat dengan status "Dibatalkan"
    //    dan pastikan tabel sudah selesai dimuat.
    const row = page.locator('tr', { hasText: 'Dibatalkan' }).first();
    await expect(row).toBeVisible();

    // 3. Klik tombol "Keterangan" di dalam baris tersebut
    const keteranganButton = row.getByRole('button', { name: 'Keterangan' });
    await keteranganButton.click();

    // 4. Verifikasi bahwa popover muncul dan berisi teks alasan yang benar
    const popoverContent = page.locator('.popover-content');
    await expect(popoverContent).toBeVisible();
    await expect(popoverContent).toHaveText('Alasan pembatalan yang sesuai dengan data uji');

  });
});
