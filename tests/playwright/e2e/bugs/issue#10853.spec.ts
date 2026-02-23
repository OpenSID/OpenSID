import { test, expect } from '@playwright/test';
import path from 'path';

test.describe('Bug/error: Ubah tanggal periksa #10853', () => {
  test('fix: perbaikan Ubah tanggal periksa', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10853'
    }
  }, async ({ page }) => {
    // navigate to listing and pick first record
    await page.goto('stunting/pemantauan_anak');
    await page.waitForSelector('table tbody tr');

    const editLink = page.locator('a[href^="stunting/formAnak/"]').first();
    const href = await editLink.getAttribute('href');
    expect(href).toBeTruthy();

    await editLink.click();
    await page.waitForSelector('input[name="tanggal_periksa"]');

    // change date to a fixed known value
    const newDate = '01-01-2021';
    await page.fill('input[name="tanggal_periksa"]', newDate);

    await page.getByRole('button', { name: ' Simpan' }).click();
    await expect(page.getByText('Berhasil Ubah Data')).toBeVisible();

    // back to listing and verify updated date appears
    await page.goto('stunting/pemantauan_anak');
    await page.waitForSelector('table tbody tr');
    // date column is 7th index (0-based 6) according to table header
    const firstRowDate = page.locator('table tbody tr').first().locator('td').nth(6);
    await expect(firstRowDate).toContainText('2021');
  });
});
