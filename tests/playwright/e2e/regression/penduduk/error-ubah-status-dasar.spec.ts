// Issue: #11148 — Muncul error saat ubah status dasar
// https://github.com/OpenSID/OpenSID/issues/11148
import { test, expect } from '../../../utils/regressionTest';


test.describe('Bug/error: muncul error saat ubah status dasar #11148', () => {
  test('fix: perbaiki ubah status dasar pindah', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11148',
    },
  }, async ({ page }) => {
    await page.goto('penduduk');

    await page.getByText('Pilih Aksi Lihat Detail').first().click();
    await page.getByRole('link', { name: ' Ubah Status Dasar' }).click();
    await page.getByTitle('Pilih Status Dasar').click();
    await page.locator('.select2-results__option', { hasText: 'Pindah' }).click();
    await expect(page.getByText('Anggota Keluarga yang Ikut')).toBeVisible();
  });
});