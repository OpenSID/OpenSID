import { test, expect } from '@playwright/test';
import { Laravel } from '../../../laravel';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../../storage/auth/admin.json'),
});

test.describe('Validasi input data keuangan #9430', () => {
  test('fix: perbaikan validasi input data keuangan', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/9430',
    },
  }, async ({ page }) => {
    await page.goto('keuangan_manual');
    await page.getByRole('link', { name: ' Tambah Template' }).click();
    await page.getByRole('button', { name: ' Simpan' }).click();
    await page.getByRole('row', { name: '3      4.1.1     Hasil Usaha' }).getByRole('link').click();
    await page.getByPlaceholder('Nilai Anggaran').click();
    await page.getByPlaceholder('Nilai Anggaran').press('ControlOrMeta+a');
    await page.getByPlaceholder('Nilai Anggaran').fill('');
    await page.getByPlaceholder('Nilai Realisasi').click();
    await page.getByPlaceholder('Nilai Realisasi').press('ControlOrMeta+a');
    await page.getByPlaceholder('Nilai Realisasi').fill('');
    await page.getByRole('button', { name: ' Simpan' }).click();
    await expect(page.getByText('Rp.').first()).toBeVisible();
    await page.getByPlaceholder('Nilai Anggaran').click();
    await expect(page.getByPlaceholder('Nilai Anggaran')).toBeVisible();
    await page.getByText('Kolom ini diperlukan.').first().click();
    await expect(page.getByText('Rp.').nth(1)).toBeVisible();
    await expect(page.getByPlaceholder('Nilai Realisasi')).toBeVisible();
    await expect(page.getByText('Kolom ini diperlukan.').nth(1)).toBeVisible();
    await page.getByRole('button', { name: ' Batal' }).click();
    await page.getByRole('button', { name: ' Simpan' }).click();
  });
});
