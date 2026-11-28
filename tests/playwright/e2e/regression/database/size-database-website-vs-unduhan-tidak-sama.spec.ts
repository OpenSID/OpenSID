// Issue: #5706 — Informasi size database di website dengan hasil unduh tidak sama
// https://github.com/OpenSID/OpenSID/issues/5706
import { test, expect } from '../../../utils/regressionTest';


test.describe('Bug/error: Informasi size Database di website dengan hasil unduh tidak sama #5706', () => {
  test('fix: perbaiki informasi size backup database', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/5706',
    },
  }, async ({ page }) => {
    await page.goto('database');
    await expect(page.locator('#maincontent')).toContainText('Perkiraan Ukuran File Backup SQL Berdasarkan Jumlah Tabel Dan Baris Data Adalah');
  });
});
