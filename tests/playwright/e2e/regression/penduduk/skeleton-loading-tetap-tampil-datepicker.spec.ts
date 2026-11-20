// Issue: #11109 — Skeleton loading tetap tampil saat membuka datepicker
// https://github.com/OpenSID/OpenSID/issues/11109
import { test, expect } from '../../../utils/regressionTest';


test.describe('Bug/error: Skeleton loading tetap tampil saat membuka datepicker #11109', () => {
  test('fix: perbaiki skeleton loading yang tetap tampil', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11109',
    },
  }, async ({ page }) => {
    await page.goto('penduduk');

    await page.getByText('Pilih Aksi Lainnya').click();
    await page.getByRole('link', { name: ' Pencarian Spesifik' }).click();
    await expect(page.locator('#modalBox').getByText('Pencarian Spesifik')).toBeVisible();
    await page.getByPlaceholder('Pilih hari/bulan (opsional').click();
    await expect(page.locator('#modalBox').getByText('Pencarian Spesifik')).toBeVisible();
  });
});