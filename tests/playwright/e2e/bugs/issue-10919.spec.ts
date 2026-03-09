import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

/**
 * Test untuk Issue #10919:
 * Persentase yang diinput manual tidak menampilkan simbol (%) saat cetak dokumentasi pembangunan
 *
 * Bug: Nilai persentase yang diinput secara manual pada form dokumentasi pembangunan
 * tampil dengan benar pada halaman daftar (mis. 30%, 45%, 67%), namun pada halaman
 * cetak/unduh, nilai ditampilkan hanya dalam bentuk angka tanpa simbol persen (%).
 *
 * Root cause: Cast 'persentase' => 'integer' di model PembangunanDokumentasi
 * menghapus simbol % dari nilai yang tersimpan di database (VARCHAR).
 * Datatable list view mengkompensasi dengan menambahkan % kembali, namun
 * view cetak dan API transformer tidak melakukan hal yang sama.
 */

test.describe('Issue #10919 - Persentase Manual Cetak Dokumentasi Pembangunan', () => {
  let pembangunanId: string;

  test.beforeEach(async ({ page }) => {
    // Pergi ke halaman daftar pembangunan
    await page.goto('admin_pembangunan');
    await page.waitForLoadState('networkidle');
  });

  test('persentase manual tampil dengan simbol % di halaman daftar dokumentasi', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10919',
    },
  }, async ({ page }) => {
    // Tunggu tabel daftar pembangunan muncul
    await page.waitForSelector('table tbody tr', { timeout: 10000 });

    // Cari baris pertama yang memiliki tombol "Rincian dokumentasi kegiatan"
    const rincianButton = page
      .locator('table tbody tr')
      .first()
      .locator('a[href*="pembangunan_dokumentasi/dokumentasi"], a:has-text("Rincian"), a[title*="dokumentasi" i]')
      .first();

    if (await rincianButton.count() === 0) {
      test.skip(true, 'Tidak ada data pembangunan untuk diuji');
      return;
    }

    // Ambil ID pembangunan dari URL tombol rincian
    const href = await rincianButton.getAttribute('href') ?? '';
    const match = href.match(/dokumentasi\/(\d+)/);
    pembangunanId = match ? match[1] : '';

    await rincianButton.click();
    await page.waitForLoadState('networkidle');

    // Halaman dokumentasi terbuka - klik tambah data
    const tambahButton = page.locator('a[href*="form-dokumentasi"]');
    await expect(tambahButton).toBeVisible({ timeout: 5000 });
    await tambahButton.click();
    await page.waitForLoadState('networkidle');

    // Pilih mode input manual
    const manualRadio = page.locator('input[name="jenis_persentase"][value="2"]');
    await manualRadio.check();

    // Isi persentase manual (tanpa simbol %)
    const persentaseInput = page.locator('input#persentase[name="persentase"]');
    await expect(persentaseInput).toBeVisible({ timeout: 5000 });
    await persentaseInput.fill('45');

    // Isi keterangan
    await page.locator('textarea#keterangan[name="keterangan"]').fill('Test persentase manual issue 10919');

    // Submit form
    await page.locator('button[type="submit"], input[type="submit"]').click();
    await page.waitForLoadState('networkidle');

    // Verifikasi kembali ke halaman daftar dokumentasi
    await page.waitForSelector('table#tabeldata', { timeout: 10000 });

    // Tunggu DataTables selesai memuat data
    await page.waitForSelector('table#tabeldata tbody tr:not(.dataTables_empty)', { timeout: 15000 });

    // Verifikasi nilai persentase di daftar tampil dengan simbol %
    const persentaseCell = page.locator('table#tabeldata tbody tr td').filter({ hasText: /\d+%/ });
    await expect(persentaseCell.first()).toBeVisible({ timeout: 10000 });

    const cellText = await persentaseCell.first().textContent();
    expect(cellText).toMatch(/\d+%/);
  });

  test('persentase manual tampil dengan simbol % di halaman cetak dokumentasi', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10919',
    },
  }, async ({ page }) => {
    // Tunggu tabel daftar pembangunan muncul
    await page.waitForSelector('table tbody tr', { timeout: 10000 });

    // Cari baris pertama
    const rincianButton = page
      .locator('table tbody tr')
      .first()
      .locator('a[href*="pembangunan_dokumentasi/dokumentasi"], a[title*="dokumentasi" i]')
      .first();

    if (await rincianButton.count() === 0) {
      test.skip(true, 'Tidak ada data pembangunan untuk diuji');
      return;
    }

    const href = await rincianButton.getAttribute('href') ?? '';
    const idMatch = href.match(/dokumentasi\/(\d+)/);
    const id = idMatch ? idMatch[1] : '';

    if (!id) {
      test.skip(true, 'Tidak dapat menemukan ID pembangunan');
      return;
    }

    // Langsung akses halaman cetak dokumentasi
    // Perlu POST ke daftar (dengan data pamong), simulasikan dengan GET menggunakan URL langsung
    // Buka halaman dialog cetak terlebih dahulu
    await page.goto(`pembangunan_dokumentasi/dialog/${id}/cetak`);
    await page.waitForLoadState('networkidle');

    // Tunggu form ttd pamong muncul
    await page.waitForSelector('select[name="pamong_ttd"], select[name="pamong_ketahui"]', { timeout: 10000 });

    // Pilih pamong penanda tangan (pilih opsi pertama yang valid)
    const pamongTtd = page.locator('select[name="pamong_ttd"]');
    const pamongKetahui = page.locator('select[name="pamong_ketahui"]');

    const ttdOptions = await pamongTtd.locator('option').count();
    if (ttdOptions > 1) {
      await pamongTtd.selectOption({ index: 1 });
    }

    const ketahuiOptions = await pamongKetahui.locator('option').count();
    if (ketahuiOptions > 1) {
      await pamongKetahui.selectOption({ index: 1 });
    }

    // Klik tombol cetak
    const cetakButton = page.locator('button[type="submit"], input[type="submit"]').first();
    
    // Gunakan waitForNavigation untuk menangkap halaman cetak yang terbuka
    const [cetakPage] = await Promise.all([
      page.context().waitForEvent('page', { timeout: 15000 }),
      cetakButton.click(),
    ]).catch(async () => {
      // Jika tidak ada popup, cek di halaman yang sama
      await page.waitForLoadState('networkidle');
      return [page];
    });

    const targetPage = cetakPage ?? page;
    await targetPage.waitForLoadState('networkidle');

    // Periksa konten halaman cetak - cari elemen h4 yang berisi persentase
    const h4Elements = targetPage.locator('h4');
    const h4Count = await h4Elements.count();

    if (h4Count > 0) {
      // Ambil semua teks dari elemen h4
      const allTexts: string[] = [];
      for (let i = 0; i < h4Count; i++) {
        const text = await h4Elements.nth(i).textContent() ?? '';
        if (text.trim()) {
          allTexts.push(text.trim());
        }
      }

      // Jika ada data dokumentasi, verifikasi persentase tampil dengan %
      if (allTexts.length > 0) {
        // Setidaknya satu h4 harus mengandung simbol %
        // Ini memverifikasi bahwa bug #10919 telah diperbaiki
        const hasPercentage = allTexts.some(text => text.includes('%'));
        
        // Jika ada angka di h4, harus ada simbol %
        const numericH4 = allTexts.filter(text => /\d/.test(text));
        if (numericH4.length > 0) {
          expect(
            numericH4.some(text => text.includes('%')),
            `Persentase harus tampil dengan simbol % di halaman cetak. Teks h4 ditemukan: ${numericH4.join(', ')}`
          ).toBe(true);
        }
      }
    }
  });

  test('verifikasi model mengembalikan persentase dengan simbol % melalui API', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10919',
    },
  }, async ({ page }) => {
    // Akses API internal pembangunan untuk memverifikasi transformer
    // Cek endpoint API yang digunakan oleh tema frontend
    const apiResponse = await page.request.get('api/pembangunan?include=pembangunan_dokumentasi');
    
    if (apiResponse.status() !== 200) {
      // Coba endpoint alternatif
      const altResponse = await page.request.get('pembangunan?format=json');
      if (altResponse.status() !== 200) {
        test.skip(true, 'API endpoint tidak tersedia');
        return;
      }
    }

    const json = await apiResponse.json().catch(() => null);
    if (!json) {
      test.skip(true, 'Response API bukan JSON valid');
      return;
    }

    // Traversal data untuk memverifikasi persentase
    const data = json.data ?? json.results ?? json;
    if (Array.isArray(data)) {
      for (const item of data) {
        const docs = item.pembangunan_dokumentasi ?? item.dokumentasi ?? [];
        for (const doc of Array.isArray(docs) ? docs : []) {
          if (doc.persentase !== null && doc.persentase !== undefined && doc.persentase !== '') {
            // Persentase dari API harus mengandung simbol %
            expect(
              String(doc.persentase).includes('%'),
              `persentase "${doc.persentase}" harus mengandung simbol % setelah perbaikan bug #10919`
            ).toBe(true);
          }
        }
      }
    }
  });
});
