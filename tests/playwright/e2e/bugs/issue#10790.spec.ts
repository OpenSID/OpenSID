import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Tampilan Hasil Cetak Kolom "Pendidikan dan Pekerjaan" Lampiran F-1.06 #10790', () => {
  test('fix: perbaikan Tampilan Hasil Cetak Lampiran F-1.06', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10790',
    },
  }, async ({ page }) => {
    // URL ini adalah tebakan berdasarkan nama file template (f-1.06).
    // Mungkin perlu disesuaikan jika slug surat yang sebenarnya berbeda.
    await page.goto('/surat/pratinjau/f-1-06');

    // Tunggu hingga halaman selesai dimuat
    await page.waitForLoadState('networkidle');

    // === Pemeriksaan 1: Jarak baris pada tabel data pemohon (Header) ===
    const rows = page.locator('table.disdukcapil tr');
    // Ambil baris kedua (Nama Lengkap) dan ketiga (NIK)
    const firstRowBox = await rows.nth(1).boundingBox();
    const secondRowBox = await rows.nth(2).boundingBox();

    expect(firstRowBox, 'Bounding box untuk baris "Nama Lengkap" harus ada.').not.toBeNull();
    expect(secondRowBox, 'Bounding box untuk baris "NIK" harus ada.').not.toBeNull();

    if (firstRowBox && secondRowBox) {
      // Pastikan ada jarak vertikal antara baris pertama dan kedua untuk menghindari tumpang tindih.
      // Bagian atas baris kedua harus berada di bawah bagian bawah baris pertama.
      expect(secondRowBox.y, 'Baris NIK harus berada di bawah baris Nama.').toBeGreaterThan(firstRowBox.y + (firstRowBox.height / 2));
    }

    // === Pemeriksaan 2: Posisi paragraf penutup ===
    // Memeriksa perbaikan untuk teks penutup yang naik ke atas.
    const lastTable = page.locator('table.tg').last();
    const closingParagraph = page.locator('p', { hasText: 'Terlampir disampaikan fotokopi' });

    const lastTableBoundingBox = await lastTable.boundingBox();
    const paragraphBoundingBox = await closingParagraph.boundingBox();

    expect(lastTableBoundingBox, 'Bounding box untuk tabel terakhir harus ada.').not.toBeNull();
    expect(paragraphBoundingBox, 'Bounding box untuk paragraf penutup harus ada.').not.toBeNull();

    if (lastTableBoundingBox && paragraphBoundingBox) {
      // Pastikan paragraf penutup berada di bawah tabel terakhir.
      expect(paragraphBoundingBox.y, 'Paragraf penutup harus berada di bawah tabel terakhir.').toBeGreaterThanOrEqual(lastTableBoundingBox.y + lastTableBoundingBox.height);
    }
  });
});