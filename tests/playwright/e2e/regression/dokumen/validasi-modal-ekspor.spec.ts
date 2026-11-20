// Issue: #11159 — Validasi modal ekspor dokumen
// https://github.com/OpenSID/OpenSID/issues/11159
import { test, expect } from '../../../utils/regressionTest';

test.describe('Validasi Modal Ekspor Dokumen #11159', () => {

  test('menampilkan modal ekspor saat tombol ekspor diklik', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11159',
    },
  }, async ({ page }) => {
    // 1. Pergi ke halaman dokumen
    await page.goto('dokumen');
    await page.waitForLoadState('domcontentloaded');

    // 2. Klik tombol Ekspor
    // Menggunakan regex untuk memastikan mencocokkan teks "Ekspor" secara tepat
    const btnEkspor = page.getByRole('link', { name: /Ekspor/i });
    await expect(btnEkspor).toBeVisible({ timeout: 15000 });
    await btnEkspor.click();

    // 3. Pastikan modal tampil
    // Konten modal dimuat lewat AJAX, beri timeout 15s
    const modalTitle = page.locator('#modalBox .modal-title');
    await expect(modalTitle).toBeVisible({ timeout: 15000 });
    await expect(modalTitle).toHaveText('Ekspor', { timeout: 15000 });

    // Pastikan konten di dalam modal juga termuat
    const modalBody = page.locator('#modalBox .modal-body');
    await expect(modalBody).toBeVisible({ timeout: 15000 });
    await expect(modalBody).toContainText('Ekspor data dan dokumen informasi publik', { timeout: 15000 });
  });

});
