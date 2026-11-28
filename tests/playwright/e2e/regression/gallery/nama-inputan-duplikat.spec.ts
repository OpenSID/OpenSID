// Issue: #9782 — Nama inputan sama/duplikat di galeri
// https://github.com/OpenSID/OpenSID/issues/9782
import { test, expect } from '../../../utils/regressionTest';


test.describe('Bug/error: Nama Inputan sama #9782', () => {
  test('fix: perbaiki nama inputan sama pada form galeri', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/9782',
    },
  }, async ({ page }) => {
    await page.goto('gallery');
    await expect(page.getByRole('link', { name: ' Tambah' })).toBeVisible();
    await page.getByRole('link', { name: ' Tambah' }).click();
    await expect(page.getByRole('heading', { name: 'Daftar Album' })).toBeVisible();
    await expect(page.getByText('Nama Album')).toBeVisible();
  });
});