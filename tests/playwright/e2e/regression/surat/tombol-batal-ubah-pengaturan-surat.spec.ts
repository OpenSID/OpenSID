// Issue: #9448 — Fungsi tombol batal pada ubah data pengaturan surat
// https://github.com/OpenSID/OpenSID/issues/9448
import { test, expect } from '../../../utils/regressionTest';


test.describe('Bug/error: Fungsi tombol batal pada ubah data Pengaturan Surat #9448', () => {
  test('fix: perbaiki tombol batal pada form ubah data pengaturan surat', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/9448',
    },
  }, async ({ page }) => {
    await page.goto('surat_master');
    await page.waitForLoadState('networkidle');

    // Filter DataTable ke 'Keterangan Kurang Mampu' agar tidak bergantung pada urutan halaman
    // (DataTable punya banyak baris Harga Tanah Test yang mendorong baris ini ke halaman 2+)
    await page.getByRole('searchbox', { name: 'Cari:' }).fill('Keterangan Kurang Mampu');
    await page.waitForSelector('table tbody tr:has-text("Keterangan Kurang Mampu")', { timeout: 15000 });

    // Klik tombol Ubah pada baris surat 'Keterangan Kurang Mampu' secara spesifik
    await page.locator('table tbody tr').filter({ hasText: 'Keterangan Kurang Mampu' }).locator('a.btn.btn-warning').first().click();
    await page.waitForURL(/surat_master\/form\/\d+/);
    await expect(page.getByRole('heading', { name: 'Surat Keterangan Kurang Mampu' })).toBeVisible({ timeout: 10000 });
    await expect(page.getByText('Syarat Surat')).toBeVisible();
    await expect(page.locator('table thead th', { hasText: 'NAMA DOKUMEN' }).first()).toBeVisible();
    await page.getByRole('button', { name: 'Batal' }).click();
    await expect(page.getByText('Syarat Surat')).toBeVisible();
    await expect(page.locator('table thead th', { hasText: 'NAMA DOKUMEN' }).first()).toBeVisible();
  });
});
