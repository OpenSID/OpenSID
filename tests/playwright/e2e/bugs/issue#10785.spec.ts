import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Tidak bisa simpan data isisan ketenagakerjaan di bagian keterangan sosial DTKS/DTSEN #10785', () => {
  test('fix: perbaiki tidak bisa simpan data isisan ketenagakerjaan di bagian keterangan sosial', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10785',
    },
  }, async ({ page }) => {
    await page.goto('dtks');
    await page.getByTitle('Lihat & Ubah Data').first().click();
    await page.getByRole('link', { name: 'IV. KETERANGAN SOSIAL EKONOMI' }).click();
    await page.getByRole('link', { name: 'Terisi 0 /' }).click();
    await page.locator('#select2-pilihan_4_416a-container').click();
    await page.getByRole('treeitem', { name: 'Tidak' }).click();
    await page.locator('#select2-pilihan_4_419-container').click();
    await page.getByRole('treeitem', { name: 'Tidak ada' }).click();
    await page.locator('#form-4-ketenagakerjaan > .col-sm-12 > .btn').click();
    await page.locator('#form-4-ketenagakerjaan > .col-sm-12 > .btn').click();
    await expect(page.locator('.swal2-success-circular-line-left')).toBeVisible();
  });
});