// Issue: #10723 — Tidak ada data yang ditampilkan saat cetak buku tamu
// https://github.com/OpenSID/OpenSID/issues/10723
import { test, expect } from '../../../utils/regressionTest';


test.describe('Bug/error: tidak ada data yang di tampilkan saat cetak buku tamu #10723', () => {
  test('fix: perbaiki cetak dan unduh data buku tamu', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10723',
    },
  }, async ({ page }) => {
    await page.goto('buku_tamu?status=null');
    const page1Promise = page.waitForEvent('popup');
    await page.getByText('Cetak', { exact: true }).click();
    const page1 = await page1Promise;
    await expect(page1.getByRole('heading', { name: /buku tamu/i })).toBeVisible();
    await expect(page1.getByRole('cell', { name: '1', exact: true })).toBeVisible();
  });
});
