import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

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
      await page.getByRole('button', { name: ' Lanjut' }).click();
      await page.getByRole('link', { name: 'F-2.01-KELAHIRAN' }).click();
    });

    await test.step('Verifikasi jenis kelamin pada lampiran', async () => {
      await expect(page.locator('#mce_24_ifr').contentFrame().getByRole('cell', { name: 'Laki-laki' }).first()).toBeVisible();
      await expect(page.locator('#mce_24_ifr').contentFrame().getByRole('cell', { name: 'Perempuan' }).first()).toBeVisible();
    });
  });
});