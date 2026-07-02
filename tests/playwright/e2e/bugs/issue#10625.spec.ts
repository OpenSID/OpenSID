import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Margin Kertas Custom tidak sama saat preview Surat #10625', () => {
  test('fix: Margin Kertas Custom tidak sama saat preview Surat', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10625',
    },
  }, async ({ page }) => {
    const NAMA_SURAT = 'Surat Keterangan Biodata Penduduk';
    const MARGIN_KUSTOM = {
      atas: '2.5',
      bawah: '2.1',
      kiri: '3.2',
      kanan: '3.3',
    };

    // Fungsi untuk membersihkan (mengembalikan ke margin global)
    const cleanup = async () => {
      await page.goto('/surat_master');
      const row = page.getByRole('row', { name: NAMA_SURAT });
      await row.getByRole('link', { name: 'Ubah' }).click();
      await expect(page).toHaveURL(/.*surat_master\/form\/\d+/);
      await page.getByLabel('Ya', { exact: true }).check();
      await page.getByRole('button', { name: 'Simpan' }).click();
      await expect(page.locator('div.alert.alert-success')).toBeVisible();
    };

    // Gunakan try...finally untuk memastikan cleanup selalu dijalankan setelah tes
    try {
      // --- 1. Pengaturan: Atur margin kustom untuk surat ---
      await page.goto('/surat_master');
      const barisSurat = page.getByRole('row', { name: NAMA_SURAT });
      await barisSurat.getByRole('link', { name: 'Ubah' }).click();
      await expect(page).toHaveURL(/.*surat_master\/form\/\d+/);

      // Pilih 'Tidak' untuk 'Gunakan Margin Kertas Global'
      await page.getByLabel('Tidak', { exact: true }).check();
      
      // Isi nilai margin kustom
      await page.locator('input[name="margin_atas"]').fill(MARGIN_KUSTOM.atas);
      await page.locator('input[name="margin_bawah"]').fill(MARGIN_KUSTOM.bawah);
      await page.locator('input[name="margin_kiri"]').fill(MARGIN_KUSTOM.kiri);
      await page.locator('input[name="margin_kanan"]').fill(MARGIN_KUSTOM.kanan);
      
      // Simpan pengaturan
      await page.getByRole('button', { name: 'Simpan' }).click();
      await expect(page.locator('div.alert.alert-success')).toBeVisible();

      // --- 2. Verifikasi: Periksa pratinjau surat ---
      await page.goto('/surat');
      const barisBuatSurat = page.getByRole('row', { name: NAMA_SURAT });
      await barisBuatSurat.getByRole('button', { name: 'Buat Surat' }).click();
      await page.waitForURL(/.*\/surat\/form\/.*/);

      // Pilih penduduk (ganti NIK jika perlu dengan data valid di database Anda)
      await page.locator('#nik + .select2-container').click();
      await page.locator('.select2-search__field').fill('3515121203870001');
      await page
        .locator('.select2-results__option', {
          hasText: /.*3515121203870001.*/,
        })
        .click();

      // Lanjutkan ke pratinjau
      await page.getByRole('button', { name: 'Pratinjau' }).click();
      await expect(page).toHaveURL(/.*surat\/pratinjau\/.*/);

      // Buka modal pengaturan
      await page.getByRole('button', { name: 'Pengaturan' }).click();
      await expect(page.locator('#modal-pengaturan')).toBeVisible();

      // Verifikasi bahwa nilai margin di modal sudah benar
      const modal = page.locator('#modal-pengaturan');
      await expect(modal.locator('input[name="margin_atas"]')).toHaveValue(MARGIN_KUSTOM.atas);
      await expect(modal.locator('input[name="margin_bawah"]')).toHaveValue(MARGIN_KUSTOM.bawah);
      await expect(modal.locator('input[name="margin_kiri"]')).toHaveValue(MARGIN_KUSTOM.kiri);
      await expect(modal.locator('input[name="margin_kanan"]')).toHaveValue(MARGIN_KUSTOM.kanan);

    } finally {
      // --- 3. Pembersihan: Selalu kembalikan ke pengaturan awal ---
      await cleanup();
    }
  });
});
