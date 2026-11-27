// Issue: #10647 — Nama ayah/ibu pakai petik tidak sesuai
// https://github.com/OpenSID/OpenSID/issues/10647
import { test, expect } from '../../../utils/regressionTest';


test.describe('Nama Ayah / Ibu pakai petik tidak sesuai #10647', () => {
  test('fix: perbaikan penambahan validasi nama tidak boleh menggunakan tanda petik', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10647',
    },
  }, async ({ page }) => {
    await page.goto('penduduk/form_peristiwa/1');
    await page.getByRole('textbox', { name: 'Nama Lengkap (Tanpa Gelar)' }).click();
    await page.getByRole('textbox', { name: 'Nama Lengkap (Tanpa Gelar)' }).fill('ma\'unah');
    await page.getByRole('button', { name: ' Simpan' }).click();
    await expect(page.locator('#mainform')).toContainText('Hanya boleh berisi karakter alpha, spasi, titik, koma, dan strip');
  });
});
