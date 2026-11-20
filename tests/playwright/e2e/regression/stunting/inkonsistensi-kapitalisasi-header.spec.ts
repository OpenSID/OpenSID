// Issue: #9669 — Inkonsistensi kapitalisasi table header pada halaman stunting
// https://github.com/OpenSID/OpenSID/issues/9669
import { test, expect } from '../../../utils/regressionTest';


test.describe('Bug/error: Inkonistensi kapitalisasi table header pada Halaman Stunting #9669', () => {
  test('fix: Sesuaikan tombol lihat laporan hasil klasifikasi', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/9669',
    },
  }, async ({ page }) => {
    await page.goto('http://localhost:8000/index.php/stunting/scorecard_konvergensi/2/2025');
    await expect(page.getByRole('rowgroup')).toContainText('Hijau (Normal)');
    await expect(page.getByRole('rowgroup')).toContainText('Kuning (Risiko Stunting)');
    await expect(page.getByRole('rowgroup')).toContainText('Merah (Terindikasi Stunting)');
  });
});