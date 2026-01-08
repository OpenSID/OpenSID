import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Hasil export ke excel di pemantauan di menu stunting jadi opensid.gpx #10704', () => {
  test('fix: perbaiki Hasil export ke excel di pemantauan di menu stunting jadi opensid.gpx', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10704',
    },
  }, async ({ page }) => {
    await page.goto('stunting/pemantauan_ibu_hamil');

    // Pastikan tombol export gpx tidak ada
    await expect(page.locator('#exportGPX')).toHaveCount(0);

    // Pastikan tombol export excel ada
    const excelButton = page.locator('#excel');
    await expect(excelButton).toBeVisible();

    // Pastikan text-nya sesuai
    await expect(excelButton).toContainText('Ekspor ke excel');

    // Pastikan icon-nya sesuai
    await expect(excelButton.locator('i')).toHaveClass(/fa-file-excel-o/);
  });
});
