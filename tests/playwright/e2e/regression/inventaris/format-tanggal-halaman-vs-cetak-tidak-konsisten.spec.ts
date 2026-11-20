// Issue: #10662 — Format tanggal inventaris antara halaman dan hasil cetak tidak konsisten
// https://github.com/OpenSID/OpenSID/issues/10662
import { test, expect } from '../../../utils/regressionTest';


test.describe('Bug/error: Format Tanggal Inventaris antara Halaman dan Hasil Cetak tidak Konsisten #10662', () => {
  test('fix: perbaikan Format Tanggal Inventaris antara Halaman dan Hasil Cetak tidak Konsisten', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10662',
    },
  }, async ({ page }) => {
    // Langkah 1: Navigasi ke halaman daftar inventaris gedung
    await page.goto('inventaris_gedung');

    // Tunggu DataTables selesai render baris data (kolom ke-7 adalah "Tanggal")
    const kolomTanggalDiHalaman = page.locator('#tabeldata tbody tr:first-child td:nth-child(7)');
    await expect(kolomTanggalDiHalaman).toBeVisible();

    // Ambil teks tanggal dari halaman daftar
    const tanggalDiHalaman = (await kolomTanggalDiHalaman.textContent())?.trim();
    expect(tanggalDiHalaman).toBeTruthy();

    // Langkah 2: POST ke endpoint cetak untuk mendapatkan HTML halaman cetak
    // Gunakan origin dari URL halaman saat ini untuk mendukung berbagai environment
    const origin = new URL(page.url()).origin;
    const cookies = await page.context().cookies();
    const csrfToken = cookies.find(c => c.name === 'sidcsrf')?.value ?? '';

    const cetakResponse = await page.request.post(`${origin}/inventaris_gedung/cetak/cetak`, {
      form: {
        sidcsrf: csrfToken,
        tahun: '',
        pamong: '16',
      },
    });
    expect(cetakResponse.ok()).toBeTruthy();

    // Langkah 3: Muat HTML cetak dan ambil tanggal dari kolom ke-10 (kolom "Tanggal" di tabel cetak)
    await page.setContent(await cetakResponse.text());
    await page.waitForSelector('#inventaris tbody tr');

    const tanggalDiCetak = (await page
      .locator('#inventaris tbody tr:first-child td:nth-child(10)')
      .textContent())?.trim();

    // Langkah 4: Pastikan format tanggal konsisten antara halaman daftar dan halaman cetak
    expect(tanggalDiCetak).toBe(tanggalDiHalaman);
  });
});
