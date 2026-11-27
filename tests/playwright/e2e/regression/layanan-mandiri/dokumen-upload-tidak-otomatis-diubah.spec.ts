// Issue: #10938 — Dokumen yang diupload melalui layanan mandiri tidak otomatis dapat diubah oleh warga
// https://github.com/OpenSID/OpenSID/issues/10938
import { test, expect } from '../../../utils/regressionTest';


test.describe('Bug/error: Dokumen yang diupload melalui Layanan Mandiri tidak otomatis dapat diubah oleh warga #10938', () => {
  test('fix: perbaiki dokumen yang diupload warga otomatis dapat diubah', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10938',
    },
  }, async ({ page }) => {
    await page.goto('penduduk/dokumen/98');

    await page.getByTitle('Ubah').click();
    await expect(page.getByLabel('Boleh diubah oleh warga')).toBeVisible();
  });
});
