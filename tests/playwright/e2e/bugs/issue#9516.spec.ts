import { test, expect} from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Format nilai pada kolom Persentase (%) tidak konsisten pada tabel Laporan Keuangan#9516', () => {
  test('fix: perbaiki form impor klasifikasi surat', {
    annotation: {
        type: 'issue',
        description: 'https://github.com/OpenSID/OpenSID/issues/9516'
    }
  }, async ({ page }) => {
    await page.goto('keuangan/laporan?jenis=rincian_realisasi_bidang_manual&tahun=2022');

    await expect(page.locator('td:nth-child(7)').first()).toContainText('0,00');
  });
});