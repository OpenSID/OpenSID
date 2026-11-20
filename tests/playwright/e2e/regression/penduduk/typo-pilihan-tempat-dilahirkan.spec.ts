// Issue: #9781 — Typo pilihan data tempat dilahirkan
// https://github.com/OpenSID/OpenSID/issues/9781
import { test, expect } from '../../../utils/regressionTest';


test.describe('Bug/error: Typo pilihan data tempat dilahirkan #9781', () => {
  test('fix: perbaiki typo pilihan data tempat dilahirkan', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/9781',
    },
  }, async ({ page }) => {
    await page.goto('penduduk');
    await page.getByText('Tambah Penduduk').click();
    await page.getByRole('link', { name: ' Penduduk Masuk' }).click();
    await expect(page.locator('select[name="tempat_dilahirkan"]')).toBeVisible();
    await expect(page.locator('select[name="tempat_dilahirkan"] > option[value="2"]')).toHaveText('PUSKESMAS');
    await expect(page.getByRole('link', { name: ' Kembali Ke Daftar Penduduk' })).toBeVisible();
    await page.getByRole('link', { name: ' Kembali Ke Daftar Penduduk' }).click();
    await page.getByText('Tambah Penduduk').click();
    await page.getByRole('link', { name: ' Penduduk Lahir' }).click();
    await expect(page.locator('select[name="tempat_dilahirkan"] > option[value="2"]')).toHaveText('PUSKESMAS');
  });
});