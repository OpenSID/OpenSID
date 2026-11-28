// Issue: #9487 — Jenis kelamin pada surat keterangan kelahiran
// https://github.com/OpenSID/OpenSID/issues/9487
import { test, expect } from '../../../utils/regressionTest';


test.describe('Bug/error: Jenis kelamin "Data Anak" pada lampiran F-2.01-KELAHIRAN #9487', () => {
  test('fix: perbaiki jenis kelamin pada lampiran f-2.01', {
    annotation: {
        type: 'issue',
        description: 'https://github.com/OpenSID/OpenSID/issues/9487'
    }
  }, async ({ page }) => {
    const testData = {
      nik: '1505021205040008',
      name: 'M. RIZKI ZAHRAN',
      hubungan: 'Paman',
    };

    await test.step('Navigasi ke halaman form surat keterangan kelahiran', async () => {
      await page.goto('surat/form/sistem-surat-keterangan-kelahiran');
    });

    await test.step('Pilih NIK dari dropdown', async () => {
      await page.locator('.select2-selection__arrow').first().click();
      await page.locator('input[type="search"]').fill('a');
      await page.getByRole('treeitem', { name: `NIK/Tag ID Card : ${testData.nik} - ${testData.name}` }).click();
    });

    await test.step('Isi hubungan pelapor dengan bayi', async () => {
      await page.getByRole('textbox', { name: 'Hubungan Pelapor dengan Bayi' }).click();
      await page.getByRole('textbox', { name: 'Hubungan Pelapor dengan Bayi' }).fill(testData.hubungan);
    });

    await test.step('Lanjutkan ke lampiran F-2.01', async () => {
      await page.getByRole('button', { name: ' Lanjut' }).click();
      await page.getByRole('link', { name: 'F-2.01-KELAHIRAN' }).click();
    });

    await test.step('Verifikasi jenis kelamin pada lampiran', async () => {
      // Gunakan struktur tab-pane bukan #mce_N_ifr — ID TinyMCE bergantung pada
      // counter internal yang bisa berubah, sementara .tab-pane.active stabil.
      const lampiranFrame = page.locator('.tab-pane.active iframe').first().contentFrame();
      await expect(lampiranFrame.getByRole('cell', { name: 'Laki-laki' }).first()).toBeVisible({ timeout: 15000 });
      await expect(lampiranFrame.getByRole('cell', { name: 'Perempuan' }).first()).toBeVisible({ timeout: 15000 });
    });
  });
});
