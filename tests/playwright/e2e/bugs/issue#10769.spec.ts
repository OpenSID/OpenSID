import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('nomor surat tidak masuk di lampiran F-1.34 #10769', () => {
  test('fix: perbaikan nomor surat tidak masuk di lampiran F-1.34', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10769',
    },
  }, async ({ page }) => {
    await page.goto('/surat/form/sistem-surat-keterangan-pindah-penduduk');

    // 1. Ambil nomor surat dari input untuk divalidasi nanti
    const nomorSuratInput = await page.locator('input[name="nomor"]').inputValue();
    
    // 2. Isi Data Pemohon (Pilih dari autocomplete/select2)
    await page.locator('.select2-selection').first().click();
    await page.locator('.select2-search__field').fill('MUHAMAD'); // Contoh input sesuai video
    await page.locator('.select2-results__option--highlighted').click();

    // 3. Isi Klasifikasi Pindah & Alamat (Contoh pengisian sesuai video)
    await page.selectOption('select[name="id_klasifikasi_pindah"]', { label: 'Pindah Penduduk' });
    await page.selectOption('select[name="id_alasan_pindah"]', { label: 'PEKERJAAN' });
    await page.locator('textarea[name="alamat_tujuan"]').fill('Jalan Banglas Gang Antara');
    
    // 4. Isi RT/RW & Kode Pos
    await page.locator('input[name="rt_tujuan"]').fill('01');
    await page.locator('input[name="rw_tujuan"]').fill('02');
    await page.locator('input[name="kode_pos_tujuan"]').fill('28421');

    // 5. Pilih Anggota Keluarga yang Pindah (Centang checkbox di tabel)
    await page.locator('table#tabel-keluarga tbody tr:first-child input[type="checkbox"]').check();

    // 6. Klik tombol Cetak (untuk masuk ke halaman pratinjau/preview)
    await page.getByRole('button', { name: /Cetak/i }).click();

    // 7. Validasi pada halaman Tinjau Surat
    await expect(page.getByRole('heading', { name: 'Tinjau Surat' })).toBeVisible();

    // Karena cetakan surat menggunakan iframe TinyMCE, kita harus masuk ke dalam frame-nya
    const frame = page.frameLocator('#isi_surat_ifr');
    
    // 8. Pastikan Nomor Surat yang diinput tadi muncul di dalam konten cetakan
    // Kita mencari teks yang mengandung nomor surat di dalam frame
    await expect(frame.locator('body')).toContainText(nomorSuratInput);

    // Opsional: Validasi spesifik format "Nomor : [nomor_surat]"
    await expect(frame.locator('body')).toContainText(new RegExp(`Nomor :.*${nomorSuratInput}`, 'i'));
  });
});
