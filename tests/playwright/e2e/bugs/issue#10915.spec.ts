import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Tidak Ada Notifikasi Gagal pada simpan Buku Peraturan desa dengan format Word #10915', () => {
  test('fix: perbaiakn tidak Ada Notifikasi Gagal pada simpan Buku Peraturan desa dengan format Word', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10915',
    },
  }, async ({ page }) => {
    // -----------------------------------------------------------------------
    // 1. Navigasi ke halaman Peraturan Desa (kat=3)
    // -----------------------------------------------------------------------
    await page.goto('dokumen_sekretariat/peraturan');
    await expect(page).toHaveURL(/.*peraturan/);

    // -----------------------------------------------------------------------
    // 2. Buka form tambah Peraturan Desa
    // -----------------------------------------------------------------------
    await page.getByRole('link', { name: /tambah/i }).click();
    await expect(page).toHaveURL(/.*form\/3/);

    // -----------------------------------------------------------------------
    // 3. Isi form dengan data valid
    // -----------------------------------------------------------------------
    await page.getByLabel(/nama/i).fill('Test Peraturan Desa Otomatis');

    // Pilih jenis peraturan jika ada dropdown
    const jenisPeraturan = page.locator('select[name="attr[jenis_peraturan]"]');
    if (await jenisPeraturan.isVisible()) {
      await jenisPeraturan.selectOption({ index: 1 });
    }

    await page.locator('input[name="attr[no_ditetapkan]"]').fill('001/2026');
    await page.locator('input[name="attr[tgl_ditetapkan]"]').fill('2026-01-01');
    await page.locator('textarea[name="attr[uraian]"]').fill('Uraian peraturan desa test otomatis');

    // Pastikan tipe = upload file (bukan URL)
    const radioFile = page.locator('input[name="tipe"][value="1"]');
    if (await radioFile.isVisible()) {
      await radioFile.check();
    }

    // -----------------------------------------------------------------------
    // 4. Upload file dengan format TIDAK diizinkan (.docx / Word)
    //    Format yang diizinkan: jpg, jpeg, png, pdf
    // -----------------------------------------------------------------------
    const invalidFilePath = path.resolve(__dirname, '../../storage/testing/sample.docx');
    await page.locator('input[name="satuan"]').setInputFiles(invalidFilePath);

    // -----------------------------------------------------------------------
    // 5. Submit form
    // -----------------------------------------------------------------------
    await page.getByRole('button', { name: /simpan/i }).click();

    // -----------------------------------------------------------------------
    // 6. Verifikasi: notifikasi error muncul setelah redirect balik ke /peraturan
    //    Pesan yang diharapkan:
    //    - "Data gagal disimpan"
    //    - "Jenis berkas yang Anda unggah tidak diperbolehkan."
    // -----------------------------------------------------------------------
    await expect(page).toHaveURL(/.*peraturan/);

    const alertError = page.locator('div.alert');
    await expect(alertError).toBeVisible();
    await expect(alertError).toContainText('Data gagal disimpan');
    await expect(alertError).toContainText('Jenis berkas yang Anda unggah tidak diperbolehkan');
  });
});
