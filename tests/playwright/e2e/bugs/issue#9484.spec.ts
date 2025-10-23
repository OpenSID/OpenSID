import { test, expect} from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Keuangan Web #9484', () => {
  test('buat artikel keuangan laporan (Belanja per Bidang) - Manual', {
    annotation: {
        type: 'issue',
        description: 'https://github.com/OpenSID/OpenSID/issues/9484'
    }
  }, async ({ page }) => {
    await page.goto('web/keuangan');
    await page.getByRole('link', { name: ' Tambah Keuangan' }).click();
    await page.getByRole('textbox', { name: 'Judul Artikel' }).click();
    await page.getByRole('textbox', { name: 'Judul Artikel' }).fill('Laporan Keuangan Rincian');
    await page.getByRole('button', { name: 'Laporan Keuangan' }).click();
    await page.getByText('Tabel Laporan').click();
    await page.getByRole('button', { name: 'Pilih' }).click();
    await expect(page.locator('iframe[title="Rich Text Area"]').contentFrame().getByText('[[lap-RP-APBD-Bidang-manual,')).toBeVisible();
    await page.getByRole('button', { name: ' Simpan' }).click();
    await expect(page.getByText('Artikel berhasil ditambahkan')).toBeVisible();
  });

  test('fix: perbaiki laporan keuangan artikel web', {
    annotation: {
        type: 'issue',
        description: 'https://github.com/OpenSID/OpenSID/issues/9484'
    }
  }, async ({ page }) => {
    await page.goto('artikel/2025/04/22/laporan-keuangan-rincian-manual');
    await expect(page.getByRole('heading', { name: 'Laporan Keuangan Rincian' })).toBeVisible();
    await expect(page.getByRole('cell', { name: 'JUMLAH PENDAPATAN' })).toBeVisible();
    await expect(page.getByRole('cell', { name: 'JUMLAH BELANJA' })).toBeVisible();
    await page.getByRole('cell', { name: 'SURPLUS / (DEFISIT)' }).click();
    await page.getByRole('cell', { name: 'PEMBIAYAAN NETTO' }).click();
    await page.getByRole('cell', { name: 'SILPA/SiLPA TAHUN BERJALAN' }).click();
  });
});