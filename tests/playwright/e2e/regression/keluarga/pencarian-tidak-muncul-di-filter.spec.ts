// Issue: #10440 — Pencarian keluarga tidak muncul di filter manapun di menu keluarga
// https://github.com/OpenSID/OpenSID/issues/10440
import { test, expect } from '../../../utils/regressionTest';


test.describe('Bug/error: pencarian keluarga tidak muncul di fileter manapun di menu keluarga #10440', () => {
  test('fix: Data penduduk selain hidup (MATI/PINDAH/TIDAK VALID/DLL) masih terbaca di Data Suplemen', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10440',
    },
  }, async ({ page }) => {
    await page.goto('keluarga');
    await expect(page.getByRole('link', { name: '5201140104126994' })).toBeVisible();
    await page.getByRole('link', { name: '5201140104126994' }).click();
    await page.getByRole('link', { name: ' Kembali Ke Daftar Anggota' }).click();
    await page.getByRole('link', { name: '' }).first().click();
    await page.getByRole('textbox', { name: 'KEPALA KELUARGA' }).click();
  });
});