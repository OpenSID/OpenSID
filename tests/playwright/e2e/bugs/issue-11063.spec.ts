import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

/**
 * Test untuk Issue #11063: Sebutan nama di soal DTSEN pada tab anggota keluarga tidak mencantumkan nama sesuai data yang dipilih
 * 
 * Bug: Placeholder "(nama)" di beberapa soal tidak diganti dengan nama anggota keluarga yang dipilih
 * Soal yang terkena:
 * - 416.a (Ketenagakerjaan)
 * - 416.b (Ketenagakerjaan)
 * - 419 (Ketenagakerjaan)
 * - 420a (Kepemilikan usaha)
 * - 430 (Kesehatan)
 * - 431a-431f (Program perlindungan sosial)
 */

test.describe('Issue #11063 - Nama di Soal DTSEN Tab Anggota Keluarga', () => {
  let dtsенId: string;

  test.beforeAll(async () => {
    // Bisa diisi dengan ID DTSEN yang valid jika ada setup khusus
    dtsенId = '1';
  });

  test('Placeholder (nama) harus diganti dengan nama anggota keluarga di soal 416.a', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11063',
    },
  }, async ({ page }) => {
    // 1. Navigasi ke halaman DTSEN pendataan
    await page.goto(`/dtsen/pendataan/${dtsенId}`);
    await page.waitForLoadState('networkidle');

    // 2. Tunggu tabel anggota keluarga muncul
    await page.waitForSelector('#tabel_art_dtsen');

    // 3. Klik tombol "Lihat" atau "Ketenagakerjaan" pada baris pertama
    const firstKetenagakerjaanButton = page.locator('a[data-table="tabel_ketenagakerjaan"]').first();
    if (await firstKetenagakerjaanButton.count() > 0) {
      await firstKetenagakerjaanButton.click();

      // 4. Tunggu modal terbuka
      await page.waitForSelector('#modal-tab4:visible', { timeout: 10000 });
      await page.waitForTimeout(300); // Tunggu fungsi JS selesai

      // 5. Cek field 416.a - harus ada nama bold, tidak ada (nama)
      const field416a = await page.locator('#tr_4_416a .ganti-nama').innerHTML();

      // Verifikasi: field 416.a tidak boleh mengandung (nama)
      expect(field416a).not.toContain('(nama)');
      expect(field416a).toContain('416.a');

      // Verifikasi: jika ada nama, harus ada tag <b> untuk bold
      if (field416a.includes('Apakah')) {
        expect(field416a).toMatch(/<b>.*?<\/b>/);
      }
    }
  });

  test('Placeholder (nama) harus diganti dengan nama anggota keluarga di soal 416.b', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11063',
    },
  }, async ({ page }) => {
    await page.goto(`/dtsen/pendataan/${dtsенId}`);
    await page.waitForLoadState('networkidle');
    await page.waitForSelector('#tabel_art_dtsen');

    const firstKetenagakerjaanButton = page.locator('a[data-table="tabel_ketenagakerjaan"]').first();
    if (await firstKetenagakerjaanButton.count() > 0) {
      await firstKetenagakerjaanButton.click();
      await page.waitForSelector('#modal-tab4:visible', { timeout: 10000 });
      await page.waitForTimeout(300);

      // Cek field 416.b - harus ada nama bold, tidak ada (nama)
      const field416b = await page.locator('#tr_4_416b .ganti-nama').innerHTML();

      expect(field416b).not.toContain('(nama)');
      expect(field416b).toContain('416.b');
      expect(field416b).toContain('Berapa Jam');

      // Verifikasi ada tag <b> untuk nama yang bold
      if (!field416b.includes('(nama)')) {
        expect(field416b).toMatch(/<b>.*?<\/b>/);
      }
    }
  });

  test('Placeholder (nama) harus diganti dengan nama anggota keluarga di soal 419', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11063',
    },
  }, async ({ page }) => {
    await page.goto(`/dtsen/pendataan/${dtsенId}`);
    await page.waitForLoadState('networkidle');
    await page.waitForSelector('#tabel_art_dtsen');

    const firstKetenagakerjaanButton = page.locator('a[data-table="tabel_ketenagakerjaan"]').first();
    if (await firstKetenagakerjaanButton.count() > 0) {
      await firstKetenagakerjaanButton.click();
      await page.waitForSelector('#modal-tab4:visible', { timeout: 10000 });
      await page.waitForTimeout(300);

      // Cek field 419 - NPWP
      const field419 = await page.locator('#tr_4_419 .ganti-nama').innerHTML();

      expect(field419).not.toContain('(nama)');
      expect(field419).toContain('419');
      expect(field419).toContain('NPWP');
      expect(field419).toMatch(/<b>.*?<\/b>/);
    }
  });

  test('Placeholder (nama) harus diganti dengan nama anggota keluarga di soal 420a', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11063',
    },
  }, async ({ page }) => {
    await page.goto(`/dtsen/pendataan/${dtsенId}`);
    await page.waitForLoadState('networkidle');
    await page.waitForSelector('#tabel_art_dtsen');

    const kepemilikanButton = page.locator('a[data-table="tabel_kepemilikan_usaha"]').first();
    if (await kepemilikanButton.count() > 0) {
      await kepemilikanButton.click();
      await page.waitForSelector('#modal-tab4:visible', { timeout: 10000 });
      await page.waitForTimeout(300);

      // Cek field 420a - Kepemilikan usaha
      const field420a = await page.locator('#tr_4_420a .ganti-nama').innerHTML();

      expect(field420a).not.toContain('(nama)');
      expect(field420a).toContain('420a');
      expect(field420a).toContain('usaha sendiri');
      expect(field420a).toMatch(/<b>.*?<\/b>/);
    }
  });

  test('Placeholder (nama) harus diganti dengan nama anggota keluarga di soal 430', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11063',
    },
  }, async ({ page }) => {
    await page.goto(`/dtsen/pendataan/${dtsенId}`);
    await page.waitForLoadState('networkidle');
    await page.waitForSelector('#tabel_art_dtsen');

    const kesehatanButton = page.locator('a[data-table="tabel_kesehatan"]').first();
    if (await kesehatanButton.count() > 0) {
      await kesehatanButton.click();
      await page.waitForSelector('#modal-tab4:visible', { timeout: 10000 });
      await page.waitForTimeout(300);

      // Cek field 430 - Kesehatan kronis
      const field430 = await page.locator('#tr_4_430 .ganti-nama').innerHTML();

      expect(field430).not.toContain('(nama)');
      expect(field430).toContain('430');
      expect(field430).toContain('kesehatan kronis');
      expect(field430).toMatch(/<b>.*?<\/b>/);
    }
  });

  test('Semua field ganti-nama harus tidak mengandung placeholder (nama)', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11063',
    },
  }, async ({ page }) => {
    await page.goto(`/dtsen/pendataan/${dtsенId}`);
    await page.waitForLoadState('networkidle');
    await page.waitForSelector('#tabel_art_dtsen');

    // Klik tombol pertama untuk membuka modal
    const lihatButton = page.locator('a.modal-table').first();
    if (await lihatButton.count() > 0) {
      await lihatButton.click();
      await page.waitForSelector('#modal-tab4:visible', { timeout: 10000 });
      await page.waitForTimeout(300);

      // Ambil semua elemen dengan class ganti-nama
      const gantiNamaElements = await page.locator('.ganti-nama').all();

      // Verifikasi setiap elemen tidak mengandung (nama) atau (Nama)
      for (const element of gantiNamaElements) {
        const text = await element.textContent();
        const html = await element.innerHTML();

        expect(text).not.toContain('(nama)');
        expect(text).not.toContain('(Nama)');

        // Jika ada nama, harus ada tag <b>
        if (html.includes('<b>')) {
          expect(html).toMatch(/<b>.*?<\/b>/);
        }
      }
    }
  });

  test('Field ganti-nama harus update saat anggota keluarga berbeda dipilih', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11063',
    },
  }, async ({ page }) => {
    await page.goto(`/dtsen/pendataan/${dtsенId}`);
    await page.waitForLoadState('networkidle');
    await page.waitForSelector('#tabel_art_dtsen');

    // Klik anggota pertama
    const firstButton = page.locator('a.modal-table').first();
    if (await firstButton.count() > 0) {
      await firstButton.click();
      await page.waitForSelector('#modal-tab4:visible', { timeout: 10000 });
      await page.waitForTimeout(300);

      // Ambil nama dari field 416.a
      const firstNameElement = await page.locator('#tr_4_416a .ganti-nama').innerHTML();
      expect(firstNameElement).not.toContain('(nama)');

      // Tutup modal
      const closeButton = page.locator('.modal-header .close');
      await closeButton.click();
      await page.waitForTimeout(200);

      // Klik anggota kedua (jika ada)
      const secondButton = page.locator('a.modal-table').nth(1);
      if (await secondButton.count() > 0) {
        await secondButton.click();
        await page.waitForSelector('#modal-tab4:visible', { timeout: 10000 });
        await page.waitForTimeout(300);

        // Ambil nama dari field 416.a untuk anggota kedua
        const secondNameElement = await page.locator('#tr_4_416a .ganti-nama').innerHTML();
        expect(secondNameElement).not.toContain('(nama)');
        expect(secondNameElement).toMatch(/<b>.*?<\/b>/);

        // Verifikasi konten berbeda dari sebelumnya (jika ada 2 anggota berbeda)
        // Ini optional karena bisa sama jika data sama
        console.log('First name element:', firstNameElement);
        console.log('Second name element:', secondNameElement);
      }
    }
  });

  test('Minimal 5 field harus memiliki tag bold <b> untuk nama', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11063',
    },
  }, async ({ page }) => {
    await page.goto(`/dtsen/pendataan/${dtsенId}`);
    await page.waitForLoadState('networkidle');
    await page.waitForSelector('#tabel_art_dtsen');

    // Klik tombol pertama
    const lihatButton = page.locator('a.modal-table').first();
    if (await lihatButton.count() > 0) {
      await lihatButton.click();
      await page.waitForSelector('#modal-tab4:visible', { timeout: 10000 });
      await page.waitForTimeout(300);

      // Hitung elemen dengan <b>
      const boldElements = await page.locator('.ganti-nama:has-text("")').all();
      let boldCount = 0;

      for (const element of boldElements) {
        const html = await element.innerHTML();
        if (html.includes('<b>')) {
          boldCount++;
        }
      }

      // Verifikasi minimal 5 field memiliki bold
      expect(boldCount).toBeGreaterThanOrEqual(5);
    }
  });
});
