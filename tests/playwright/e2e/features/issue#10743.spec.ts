import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Fitur: Permintaan Fitur baru agar pengaturan nama desa berada dalam satu tempat #10743', () => {
  test('fix: Modal Pengaturan Identitas muncul saat klik icon gear di halaman identitas desa', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10743',
    },
  }, async ({ page }) => {
    // Langkah 1: Masuk halaman identitas desa
    await page.goto('identitas_desa');

    // Tunggu halaman dimuat
    await expect(page.locator('h1')).toBeVisible();

    // Langkah 2: Klik icon gear di pojok kanan atas (header)
    const gearIcon = page.locator('a[data-target="#pengaturan"] .fa-gear');
    await expect(gearIcon).toBeVisible();
    await gearIcon.click();

    // Langkah 3: Verifikasi modal "Pengaturan Identitas" muncul
    const modal = page.locator('#pengaturan');
    await expect(modal).toBeVisible();

    // Verifikasi judul modal mengandung "Pengaturan Identitas"
    const modalTitle = modal.locator('.modal-title');
    await expect(modalTitle).toBeVisible();
    await expect(modalTitle).toContainText('Pengaturan Identitas');

    // Verifikasi field "Sebutan Desa" ada di dalam modal
    const sebutanDesaInput = modal.locator('input[name="sebutan_desa"]');
    await expect(sebutanDesaInput).toBeVisible();

    // Verifikasi field "Sebutan Kecamatan" ada di dalam modal
    const sebutanKecamatanInput = modal.locator('input[name="sebutan_kecamatan"]');
    await expect(sebutanKecamatanInput).toBeVisible();

    // Verifikasi field "Sebutan Kecamatan Singkat" ada di dalam modal
    const sebutanKecamatanSingkatInput = modal.locator('input[name="sebutan_kecamatan_singkat"]');
    await expect(sebutanKecamatanSingkatInput).toBeVisible();

    // Verifikasi field "Sebutan Camat" ada di dalam modal
    const sebutanCamatInput = modal.locator('input[name="sebutan_camat"]');
    await expect(sebutanCamatInput).toBeVisible();

    // Verifikasi field "Sebutan Kabupaten" ada di dalam modal
    const sebutanKabupatenInput = modal.locator('input[name="sebutan_kabupaten"]');
    await expect(sebutanKabupatenInput).toBeVisible();

    // Verifikasi field "Sebutan Kabupaten Singkat" ada di dalam modal
    const sebutanKabupatenSingkatInput = modal.locator('input[name="sebutan_kabupaten_singkat"]');
    await expect(sebutanKabupatenSingkatInput).toBeVisible();

    // Tutup modal dengan klik tombol close
    const closeButton = modal.locator('button.close');
    await closeButton.click();

    // Verifikasi modal sudah tertutup
    await expect(modal).not.toBeVisible();
  });

  test('fix: Semua pengaturan sebutan wilayah tersedia dalam satu modal', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10743',
    },
  }, async ({ page }) => {
    // Masuk halaman identitas desa
    await page.goto('identitas_desa');

    // Klik icon gear untuk membuka modal pengaturan
    const gearIcon = page.locator('a[data-target="#pengaturan"] .fa-gear');
    await expect(gearIcon).toBeVisible();
    await gearIcon.click();

    // Tunggu modal muncul
    const modal = page.locator('#pengaturan');
    await expect(modal).toBeVisible();

    // Daftar field pengaturan sebutan wilayah yang seharusnya ada
    const expectedFields = [
      'sebutan_desa',
      'sebutan_kecamatan',
      'sebutan_kecamatan_singkat',
      'sebutan_camat',
      'sebutan_kabupaten',
      'sebutan_kabupaten_singkat',
    ];

    // Verifikasi semua field ada di dalam satu modal
    for (const fieldName of expectedFields) {
      const input = modal.locator(`input[name="${fieldName}"]`);
      await expect(input, `Field ${fieldName} harus ada di dalam modal`).toBeVisible();
    }
  });
});
