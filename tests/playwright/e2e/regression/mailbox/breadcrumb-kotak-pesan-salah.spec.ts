// Issue: #11200 — Breadcrumb daftar kotak pesan mengarah ke halaman komentar
// https://github.com/OpenSID/OpenSID/issues/11200
import { test, expect } from '../../../utils/regressionTest';


test.describe('Bug/error: Breadcrumb Daftar Kotak Pesan Mengarah ke Halaman Komentar #11200', () => {
  test('fix: perbaiki breadcrumb daftar kotak pesan', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11193',
    },
  }, async ({ page }) => {
    await page.goto('mailbox');

    await expect(page.getByRole('heading', { name: 'Kotak Pesan' })).toBeVisible();
    await page.getByTitle('Lihat detail pesan').first().click();
    await page.getByRole('link', { name: 'Daftar Kotak Pesan', exact: true }).click();
    await expect(page.getByRole('heading', { name: 'Kotak Pesan' })).toBeVisible();
  });
});