import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../../storage/auth/admin.json'),
});

test.describe('Fix: Tombol Aksi Tertahan di Laporan Per Indikator #11158', () => {
  test('Tombol Cetak tidak tertahan setelah submit (target="_blank")', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11158',
    },
  }, async ({ page, context }) => {
    test.setTimeout(30000);

    // 1. Navigate ke halaman Laporan Per Indikator
    await page.goto('analisis_statistik_jawaban/2');

    // 2. Tunggu halaman fully loaded
    await page.waitForLoadState('networkidle');

    // 3. Cari tombol Cetak
    const btnCetak = page.locator('button[name="tipe"][value="cetak"]').first();
    await expect(btnCetak).toBeVisible();

    // 4. Verifikasi tombol awal menampilkan teks "Cetak"
    const originalText = await btnCetak.innerText();
    expect(originalText).toContain('Cetak');

    // 5. Setup listener untuk menangkap popup/new window
    let newPageCaptured = false;
    const popupPromise = context.waitForEvent('page');

    // 6. Klik tombol Cetak (form submit dengan target="_blank")
    await btnCetak.click();

    // 7. Tunggu popup terbuka dengan timeout 5 detik
    try {
      const newPage = await Promise.race([
        popupPromise,
        new Promise((_, reject) => setTimeout(() => reject(new Error('Popup timeout')), 5000)),
      ]) as any;
      
      if (newPage) {
        newPageCaptured = true;
        await newPage.close();
      }
    } catch (error) {
      // Popup mungkin tidak terbuka di test environment, tapi itu OK
      // Yang penting adalah tombol di-restore dengan benar
    }

    // 8. Tunggu fix timeout (100ms) agar tombol di-restore
    await page.waitForTimeout(150);

    // 9. Verifikasi tombol sudah di-restore (PENTING: fix untuk issue ini)
    const btnTextAfter = await btnCetak.innerText();
    expect(btnTextAfter).not.toContain('Mohon tunggu');
    expect(btnTextAfter).toContain('Cetak');

    // 10. Verifikasi tombol tidak disabled
    const isDisabled = await btnCetak.isDisabled();
    expect(isDisabled).toBe(false);

    // 11. Verifikasi bisa klik tombol lagi tanpa harus refresh
    // Ini membuktikan bahwa fix berhasil mengatasi issue
    await expect(btnCetak).toBeEnabled();
    await expect(btnCetak).toBeVisible();
  });

  test('Tombol Unduh tidak tertahan setelah submit (target="_blank")', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11158',
    },
  }, async ({ page, context }) => {
    test.setTimeout(30000);

    // 1. Navigate ke halaman Laporan Per Indikator
    await page.goto('analisis_statistik_jawaban/2');
    await page.waitForLoadState('networkidle');

    // 2. Cari tombol Unduh
    const btnUnduh = page.locator('button[name="tipe"][value="unduh"]').first();
    await expect(btnUnduh).toBeVisible();

    // 3. Verifikasi tombol awal
    const originalText = await btnUnduh.innerText();
    expect(originalText).toContain('Unduh');

    // 4. Setup listener untuk popup
    const popupPromise = context.waitForEvent('page');

    // 5. Klik tombol Unduh
    await btnUnduh.click();

    // 6. Tunggu popup (dengan graceful timeout)
    try {
      const newPage = await Promise.race([
        popupPromise,
        new Promise((_, reject) => setTimeout(() => reject(new Error('Popup timeout')), 5000)),
      ]) as any;
      
      if (newPage) {
        await newPage.close();
      }
    } catch {
      // Timeout expected di test environment
    }

    // 7. Tunggu fix timeout
    await page.waitForTimeout(150);

    // 8. Verifikasi tombol sudah di-restore
    const btnTextAfter = await btnUnduh.innerText();
    expect(btnTextAfter).not.toContain('Mohon tunggu');
    expect(btnTextAfter).toContain('Unduh');
    expect(await btnUnduh.isDisabled()).toBe(false);
  });

  test('Tombol restore otomatis tanpa perlu refresh halaman', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11158',
    },
  }, async ({ page, context }) => {
    test.setTimeout(30000);

    await page.goto('analisis_statistik_jawaban/2');
    await page.waitForLoadState('networkidle');

    const btnCetak = page.locator('button[name="tipe"][value="cetak"]').first();

    // Verifikasi: Tombol bisa diklik berkali-kali tanpa refresh
    for (let i = 0; i < 3; i++) {
      await expect(btnCetak).toBeEnabled();
      
      // Setup popup listener
      const popupPromise = context.waitForEvent('page');
      
      // Klik tombol
      await btnCetak.click();
      
      // Wait untuk popup
      try {
        const newPage = await Promise.race([
          popupPromise,
          new Promise((_, reject) => setTimeout(() => reject(new Error('Popup timeout')), 5000)),
        ]) as any;
        
        if (newPage) {
          await newPage.close();
        }
      } catch {
        // Expected
      }
      
      // Wait fix timeout
      await page.waitForTimeout(150);
      
      // Verifikasi tombol sudah normal kembali
      const textAfter = await btnCetak.innerText();
      expect(textAfter).toContain('Cetak');
      expect(await btnCetak.isDisabled()).toBe(false);
    }
  });
});
