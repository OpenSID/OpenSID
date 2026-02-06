import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Gagal ubah data ketua lembaga dan jabatan berubah menjadi anggota #10811', () => {
  test('fix: Nomor SK Jabatan tidak boleh lebih dari 50 karakter', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10811',
    },
  }, async ({ page }) => {
    // Langkah 1: Masuk ke halaman /lembaga
    await page.goto('lembaga');

    // Tunggu halaman dimuat dan datatables siap
    await page.waitForLoadState('networkidle');
    await expect(page.locator('h1')).toContainText('Lembaga', { ignoreCase: true });

    // Verifikasi ada minimal 1 lembaga di tabel
    const lembagaRows = page.locator('table tbody tr');
    const lembagaCount = await lembagaRows.count();
    
    if (lembagaCount === 0) {
      test.skip();
    }

    // Langkah 2: Klik tombol Rincian Data pada baris pertama
    const firstLembagaRincianBtn = lembagaRows.first().locator('a[data-tooltip="Rincian Data"]');
    await expect(firstLembagaRincianBtn).toBeVisible();
    await firstLembagaRincianBtn.click();

    // Langkah 3: Tunggu halaman detail anggota lembaga dimuat
    await page.waitForLoadState('networkidle');
    await expect(page.locator('h1')).toContainText('Data Anggota');

    // Verifikasi datatable anggota lembaga sudah dimuat
    const anggotaRows = page.locator('table tbody tr');
    const anggotaCount = await anggotaRows.count();
    
    if (anggotaCount === 0) {
      test.skip();
    }

    // Langkah 4: Klik tombol Ubah pada baris pertama anggota lembaga
    const firstAnggotaEditBtn = anggotaRows.first().locator('a[data-tooltip="Ubah"]');
    await expect(firstAnggotaEditBtn).toBeVisible();
    await firstAnggotaEditBtn.click();

    // Langkah 5: Tunggu form ubah data anggota dimuat
    await page.waitForLoadState('networkidle');
    await expect(page.locator('form')).toBeVisible();

    // Langkah 6: Cari input field Nomor SK Jabatan
    const noSkJabatanInput = page.locator('input[name="no_sk_jabatan"]');
    await expect(noSkJabatanInput).toBeVisible();

    // Verifikasi bahwa field memiliki maxlength="50"
    const maxLengthAttr = await noSkJabatanInput.getAttribute('maxlength');
    expect(maxLengthAttr).toBe('50');

    // Verifikasi helper text "Maksimal 50 karakter" ada
    const helperText = page.locator('small.form-text.text-muted');
    await expect(helperText).toContainText('Maksimal 50 karakter');

    // Langkah 7: Coba isi dengan string lebih dari 50 karakter
    const longString = 'SSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS'; // 53 karakter
    await noSkJabatanInput.fill(longString);

    // Verifikasi bahwa value yang tersimpan hanya 50 karakter (browser enforce maxlength)
    const inputValue = await noSkJabatanInput.inputValue();
    expect(inputValue.length).toBeLessThanOrEqual(50);

    console.log('✅ Test berhasil: Nomor SK Jabatan dibatasi maksimal 50 karakter');
  });
});
