// Issue: #9795 — Informasi validasi gagal pada tab surat dinas
// https://github.com/OpenSID/OpenSID/issues/9795
import { test, expect } from '../../../utils/regressionTest';


test.describe('Informasi Validasi Gagal Pada Tab #9795', () => {
  test('fix: perbaiki Informasi Validasi Gagal Pada Tab', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/9795',
    },
  }, async ({ page }) => {
    test.setTimeout(60000);
    await page.goto('surat_dinas/pengaturan');
    await page.getByRole('link', { name: 'Lainnya' }).click();
    await page.getByRole('textbox', { name: 'd F Y' }).fill('');
    // Submit while still on Lainnya tab — format_tanggal is visible so jQuery Validate sees it
    await page.getByRole('button', { name: /Simpan/ }).click();
    await expect(page.getByText('Kolom ini diperlukan.')).toBeVisible();
    // Isi ulang field — dispatch keyup agar jQuery Validate re-evaluates dan hapus status invalid
    const tanggalField = page.locator('input[name="format_tanggal_surat_dinas"]');
    await tanggalField.fill('d F Y');
    await tanggalField.dispatchEvent('keyup');
    await page.getByRole('button', { name: /Simpan/ }).click();
    await expect(page.locator('#notifikasi')).toContainText('Berhasil Ubah Data', { timeout: 15000 });
  });
});
