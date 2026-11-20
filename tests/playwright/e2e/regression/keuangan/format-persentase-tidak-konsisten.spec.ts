// Issue: #9516 — Format nilai pada kolom persentase tidak konsisten pada tabel laporan keuangan
// https://github.com/OpenSID/OpenSID/issues/9516
import { test, expect} from '../../../utils/regressionTest';


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