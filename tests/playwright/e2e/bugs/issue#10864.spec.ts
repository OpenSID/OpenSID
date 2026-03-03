import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug: Laporan Bulanan di Statistik Keluarga Baru dari penduduk setempat Tidak terecord #10864', () => {
  // Helper: ambil data dari tabel laporan bulanan pada halaman aktif
  // Struktur tabel: No(0), Perincian(1), WNI_L(2), WNI_P(3), WNA_L(4), WNA_P(5), Jml_L(6), Jml_P(7), L+P(8), KK_L(9), KK_P(10), KK(11)
  async function getReportData(page: Parameters<Parameters<typeof test>[1]>[0]) {
    return await page.evaluate(() => {
      const rows = document.querySelectorAll('table tbody tr');
      const data: Record<string, Record<string, number>> = {};

      rows.forEach(row => {
        const cells = row.querySelectorAll('td');
        if (cells.length < 12) return;

        const label = cells[1]?.textContent?.trim() || '';

        let key = '';
        if (label.includes('awal bulan'))   key = 'awal';
        else if (label.includes('Kelahiran') || label.includes('Keluarga Baru')) key = 'lahir';
        else if (label.includes('Kematian')) key = 'mati';
        else if (label.includes('Pendatang')) key = 'datang';
        else if (label.includes('Pindah'))   key = 'pindah';
        else if (label.includes('hilang'))   key = 'hilang';
        else if (label.includes('akhir bulan')) key = 'akhir';

        const toNum = (cell: Element | null): number => {
          const text = cell?.textContent?.trim() || '0';
          return text === '-' ? 0 : parseInt(text, 10) || 0;
        };

        if (key) {
          data[key] = {
            wni_l:  toNum(cells[2]),
            wni_p:  toNum(cells[3]),
            wna_l:  toNum(cells[4]),
            wna_p:  toNum(cells[5]),
            jml_l:  toNum(cells[6]),
            jml_p:  toNum(cells[7]),
            jml:    toNum(cells[8]),
            kk_l:   toNum(cells[9]),
            kk_p:   toNum(cells[10]),
            kk:     toNum(cells[11]),
          };
        }
      });

      return data;
    });
  }

  test('fix: formula KK harus konsisten (Akhir = Awal + Baru + Datang - Mati - Pindah - Hilang)', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10864',
    },
  }, async ({ page }) => {
    await page.goto('laporan');
    await page.waitForLoadState('networkidle');

    await page.getByRole('combobox', { name: '2026' }).click();
    await page.getByRole('treeitem', { name: '2026' }).click();
    await page.waitForLoadState('networkidle');

    await page.getByRole('combobox').filter({ hasText: /Januari|Februari|Maret|April|Mei|Juni|Juli|Agustus|September|Oktober|November|Desember/ }).click();
    await page.getByRole('treeitem', { name: 'Februari' }).click();
    await page.waitForLoadState('networkidle');

    const data = await getReportData(page);

    // Verifikasi formula KK: Akhir = Awal + Baru + Datang - Mati - Pindah - Hilang
    const expectedAkhirKK =
      data.awal.kk +
      data.lahir.kk +
      data.datang.kk -
      data.mati.kk -
      data.pindah.kk -
      data.hilang.kk;

    expect(
      data.akhir.kk,
      'Formula KK: Akhir = Awal + Baru + Datang - Mati - Pindah - Hilang'
    ).toBe(expectedAkhirKK);

    // Verifikasi formula penduduk juga tidak rusak
    const expectedAkhirJml =
      data.awal.jml +
      data.lahir.jml +
      data.datang.jml -
      data.mati.jml -
      data.pindah.jml -
      data.hilang.jml;

    expect(
      data.akhir.jml,
      'Formula penduduk: Akhir = Awal + Lahir + Datang - Mati - Pindah - Hilang'
    ).toBe(expectedAkhirJml);

    // Verifikasi KK_L + KK_P = KK total untuk setiap baris
    for (const [rowKey, row] of Object.entries(data)) {
      expect(
        row.kk_l + row.kk_p,
        `Baris "${rowKey}": KK_L + KK_P harus sama dengan KK total`
      ).toBe(row.kk);
    }
  });
});
