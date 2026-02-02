import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Data statistik laporan bulanan penduduk antar bulan tidak sinkron #10796', () => {
  test('fix: akhir bulan N harus sama dengan awal bulan N+1', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10796',
    },
  }, async ({ page }) => {
    // Helper function to get report data from current page
    // Struktur tabel: No(0), Perincian(1), WNI_L(2), WNI_P(3), WNA_L(4), WNA_P(5), Jml_L(6), Jml_P(7), L+P(8), KK_L(9), KK_P(10), KK(11)
    async function getReportData() {
      return await page.evaluate(() => {
        const rows = document.querySelectorAll('table tbody tr');
        const data: Record<string, Record<string, string>> = {};
        
        rows.forEach(row => {
          const cells = row.querySelectorAll('td');
          if (cells.length >= 12) {
            const label = cells[1]?.textContent?.trim() || '';
            
            // Map row labels to keys
            let key = '';
            if (label.includes('awal bulan')) key = 'awal';
            else if (label.includes('Kelahiran')) key = 'lahir';
            else if (label.includes('Kematian')) key = 'mati';
            else if (label.includes('Pendatang')) key = 'datang';
            else if (label.includes('Pindah')) key = 'pindah';
            else if (label.includes('hilang')) key = 'hilang';
            else if (label.includes('akhir bulan')) key = 'akhir';
            
            if (key) {
              data[key] = {
                jml: cells[8]?.textContent?.trim() || '0',  // L+P (index 8)
                kk: cells[11]?.textContent?.trim() || '0',   // KK total (index 11)
              };
            }
          }
        });
        
        return data;
      });
    }

    // Helper to convert display value to number (handles "-" as 0)
    function toNumber(val: string): number {
      return val === '-' ? 0 : parseInt(val, 10) || 0;
    }

    // Test November 2025 -> December 2025 transition
    await page.goto('laporan');
    await page.waitForLoadState('networkidle');
    
    // Select year 2025 - click on the year dropdown
    await page.getByRole('combobox', { name: '2025' }).click();
    await page.getByRole('treeitem', { name: '2025' }).click();
    await page.waitForLoadState('networkidle');
    
    // Select November - click on the month dropdown
    await page.getByRole('combobox').filter({ hasText: /Januari|Februari|Maret|April|Mei|Juni|Juli|Agustus|September|Oktober|November|Desember/ }).click();
    await page.getByRole('treeitem', { name: 'November' }).click();
    await page.waitForLoadState('networkidle');
    
    const novemberData = await getReportData();
    
    // Select December - click on the month dropdown again
    await page.getByRole('combobox', { name: 'November' }).click();
    await page.getByRole('treeitem', { name: 'Desember' }).click();
    await page.waitForLoadState('networkidle');
    
    const decemberData = await getReportData();

    // Verify: Akhir November = Awal Desember (Penduduk)
    expect(
      toNumber(novemberData.akhir?.jml),
      'Akhir penduduk November harus sama dengan Awal penduduk Desember'
    ).toBe(toNumber(decemberData.awal?.jml));

    // Verify: Akhir November = Awal Desember (KK)
    expect(
      toNumber(novemberData.akhir?.kk),
      'Akhir KK November harus sama dengan Awal KK Desember'
    ).toBe(toNumber(decemberData.awal?.kk));

    // Verify formula: Akhir = Awal + Lahir + Datang - Mati - Pindah - Hilang (Desember)
    const expectedAkhirPenduduk = 
      toNumber(decemberData.awal?.jml) +
      toNumber(decemberData.lahir?.jml) +
      toNumber(decemberData.datang?.jml) -
      toNumber(decemberData.mati?.jml) -
      toNumber(decemberData.pindah?.jml) -
      toNumber(decemberData.hilang?.jml);

    expect(
      toNumber(decemberData.akhir?.jml),
      'Formula penduduk: Akhir = Awal + Lahir + Datang - Mati - Pindah - Hilang'
    ).toBe(expectedAkhirPenduduk);

    const expectedAkhirKK = 
      toNumber(decemberData.awal?.kk) +
      toNumber(decemberData.lahir?.kk) +
      toNumber(decemberData.datang?.kk) -
      toNumber(decemberData.mati?.kk) -
      toNumber(decemberData.pindah?.kk) -
      toNumber(decemberData.hilang?.kk);

    expect(
      toNumber(decemberData.akhir?.kk),
      'Formula KK: Akhir = Awal + Lahir + Datang - Mati - Pindah - Hilang'
    ).toBe(expectedAkhirKK);
  });

  test('fix: formula perhitungan laporan bulanan harus konsisten', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10796',
    },
  }, async ({ page }) => {
    await page.goto('laporan');
    await page.waitForLoadState('networkidle');
    
    // Select year 2025 and December
    await page.getByRole('combobox', { name: '2025' }).click();
    await page.getByRole('treeitem', { name: '2025' }).click();
    await page.waitForLoadState('networkidle');
    
    await page.getByRole('combobox').filter({ hasText: /Januari|Februari|Maret|April|Mei|Juni|Juli|Agustus|September|Oktober|November|Desember/ }).click();
    await page.getByRole('treeitem', { name: 'Desember' }).click();
    await page.waitForLoadState('networkidle');

    // Get all numeric values from the table
    const tableData = await page.evaluate(() => {
      const getValue = (row: Element, colIndex: number): number => {
        const cell = row.querySelectorAll('td')[colIndex];
        const text = cell?.textContent?.trim() || '0';
        return text === '-' ? 0 : parseInt(text, 10) || 0;
      };

      const rows = document.querySelectorAll('table tbody tr');
      const result: Record<string, { L: number; P: number; total: number }> = {};

      rows.forEach(row => {
        const cells = row.querySelectorAll('td');
        if (cells.length >= 10) {
          const label = cells[1]?.textContent?.trim() || '';
          
          let key = '';
          if (label.includes('awal bulan')) key = 'awal';
          else if (label.includes('Kelahiran')) key = 'lahir';
          else if (label.includes('Kematian')) key = 'mati';
          else if (label.includes('Pendatang')) key = 'datang';
          else if (label.includes('Pindah')) key = 'pindah';
          else if (label.includes('hilang')) key = 'hilang';
          else if (label.includes('akhir bulan')) key = 'akhir';
          
          if (key) {
            result[key] = {
              L: getValue(row, 6),  // Jumlah L
              P: getValue(row, 7),  // Jumlah P
              total: getValue(row, 8), // L+P
            };
          }
        }
      });

      return result;
    });

    // Verify L + P = Total for each row
    for (const [key, values] of Object.entries(tableData)) {
      expect(
        values.L + values.P,
        `${key}: L + P harus sama dengan Total`
      ).toBe(values.total);
    }

    // Verify formula for L column
    const expectedL = 
      tableData.awal.L +
      tableData.lahir.L +
      tableData.datang.L -
      tableData.mati.L -
      tableData.pindah.L -
      tableData.hilang.L;

    expect(
      tableData.akhir.L,
      'Formula kolom L: Akhir = Awal + Lahir + Datang - Mati - Pindah - Hilang'
    ).toBe(expectedL);

    // Verify formula for P column
    const expectedP = 
      tableData.awal.P +
      tableData.lahir.P +
      tableData.datang.P -
      tableData.mati.P -
      tableData.pindah.P -
      tableData.hilang.P;

    expect(
      tableData.akhir.P,
      'Formula kolom P: Akhir = Awal + Lahir + Datang - Mati - Pindah - Hilang'
    ).toBe(expectedP);
  });
});