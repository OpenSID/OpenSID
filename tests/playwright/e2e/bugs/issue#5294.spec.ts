import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error:Perbaiki klik anggota keluarga 404 #5294', () => {
  test('fix: 404 saat klik anggota keluarga', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/5294',
    },
  }, async ({ page }) => {
    await page.goto('penduduk');
    await page.getByRole('row', { name: '1  Pilih Aksi Foto Penduduk' }).getByRole('button').click();
    await page.getByRole('link', { name: ' Lihat Detail Biodata' }).click();
    await page.getByRole('link', { name: ' Anggota Keluarga' }).click();
    await expect(page.locator('#maincontent')).toContainText('Daftar Anggota Keluarga');
  });
});