// Issue: #9787 — Pengaturan surat dinas
// https://github.com/OpenSID/OpenSID/issues/9787
import { test, expect } from '../../../utils/regressionTest';


test.describe('Bug/error: Pengaturan surat dinas #9787', () => {
  test('fix: perbaiki format tanggal surat pengaturan surat dinas', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/9787',
    },
  }, async ({ page }) => {
    test.setTimeout(60000);
    await page.goto('surat_dinas/pengaturan');
    await page.getByRole('link', { name: 'Lainnya' }).click();
    // Set to known value (independent of prior test state)
    await page.getByRole('textbox', { name: 'd F Y' }).fill('d F Y');
    await page.getByRole('button', { name: /Simpan/ }).click();
    await expect(page.locator('#notifikasi')).toContainText('Berhasil Ubah Data', { timeout: 15000 });
    await page.goto('surat_dinas/pengaturan');
    await page.getByRole('link', { name: 'Lainnya' }).click();
    await expect(page.getByRole('textbox', { name: 'd F Y' })).toHaveValue('d F Y', { timeout: 15000 });
  });
});
