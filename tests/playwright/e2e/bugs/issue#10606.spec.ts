import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: di form F1.06 Penduduk yang sudah pindah masih muncul datanya #10606', () => {
  test('fix: perbaiki for surat menampilkan data keluarga tidak aktif / pindah', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10606',
    },
  }, async ({ page }) => {
    await page.goto('surat');
    await page.getByRole('row', { name: ' Buat Surat  Keterangan Beda Identitas 471.1 F-1.06 3' }).getByRole('button').first().click();
    await page.getByText('-- Cari NIK / Tag ID Card /').click();
    await page.locator('input[type="search"]').fill('ahlul');
    await page.getByText('NIK/Tag ID Card :').click();
    await expect(page.locator('#validasi div').filter({ hasText: 'NIK Nama Jenis Kelamin Tempat, Tanggal Lahir Hubungan 5201142005716996 AHLUL' }).nth(4)).toBeVisible();
  });
});
