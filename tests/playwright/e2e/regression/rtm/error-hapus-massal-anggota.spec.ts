// Issue: #10680 — Error hapus massal anggota RTM
// https://github.com/OpenSID/OpenSID/issues/10680
import { test, expect } from '../../../utils/regressionTest';


test.describe('Bug/error: Error hapus massal Anggota RTM #10680', () => {
  test('fix: perbaikan hapus massal rumah tangga dan akses rtm kosong', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10680',
    },
  }, async ({ page }) => {
    await page.goto('rtm/anggota/1');;
    await page.getByRole('link', { name: '' }).click();
    await page.locator('#checkall').check();
    await page.getByRole('link', { name: ' Hapus' }).click();
    await page.locator('#confirm-delete a').click();
    await expect(page.getByText('× Berhasil Semua anggota')).toBeVisible();
  });
});
