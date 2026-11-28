// Issue: #10731 — Dropdown pilihan penduduk kosong saat klik tombol ubah pada rekam surat perseorangan
// https://github.com/OpenSID/OpenSID/issues/10731
import { test, expect } from '../../../utils/regressionTest';


test.describe('Bug/error: Dropdown pilihan penduduk kosong saat klik tombol Ubah pada Rekam Surat Perseorangan #10731', () => {
  test('fix: perbaiki pilihan penduduk pada rekam surat perseorangan', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10731',
    },
  }, async ({ page }) => {
    await page.goto('keluar/perorangan');
    await page.getByText('-- Cari NIK / Tag ID Card /').click();
    // Ketik NIK untuk memfilter ke penduduk cluster rt='-', rw='-' (alamat tampil sebagai RT--, RW--)
    await page.locator('input.select2-search__field').fill('1505021503850099');
    await page.getByText('Alamat: RT--, RW-- DUSUN').first().click();
    await expect(page.getByRole('textbox', { name: 'NIK/Tag ID Card :' })).toBeVisible();
    await page.getByTitle('Ubah Keterangan').first().click();
    await expect(page.getByText('Ubah Keterangan')).toBeVisible();
    await page.locator('#modalBox').getByText('×').click();
    await expect(page.getByRole('textbox', { name: 'NIK/Tag ID Card :' })).toBeVisible();
  });
});
