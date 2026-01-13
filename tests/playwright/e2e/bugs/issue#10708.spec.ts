import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Pesan sukses edit data pada pengaturan surat #10708', () => {
  test('fix: perbaikan pesan sukses edit data pada pengaturan surat', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10708',
    },
  }, async ({ page }) => {
    await page.goto('surat_master/form/215');
    await expect(page.getByText('Simpan Sementara').first()).toBeVisible();
    await page.getByText('Simpan Sementara').first().click();
    await expect(page.getByText('Berhasil Ubah Data')).toBeVisible();
  });
});
