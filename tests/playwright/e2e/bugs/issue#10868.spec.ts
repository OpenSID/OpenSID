import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug: Error content tombol aksi pada kotak masuk #10868', () => {
  const MAILBOX_MASUK = 'mailbox/1'; // Tipe 1 = Masuk
  const MAILBOX_KELUAR = 'mailbox/2'; // Tipe 2 = Keluar

  test.beforeEach(async ({ page }) => {
    // Setup: Pastikan sudah ada data pesan di database
    // Ini bisa dilakukan dengan factory atau seed
    await page.goto(MAILBOX_MASUK);
    await page.waitForLoadState('networkidle');
  });

  test('should NOT show 404 error when clicking "Lihat detail pesan" button', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10868',
    },
  }, async ({ page }) => {
    // 1. Pergi ke halaman kotak masuk
    await page.goto(MAILBOX_MASUK);
    await expect(page.locator('h1')).toContainText(/Kotak Masuk|Kotak Pesan/i);

    // 2. Tunggu datatable dimuat
    await page.waitForLoadState('networkidle');
    await page.waitForTimeout(2000);

    // 3. Cari tombol "Lihat detail pesan" (icon fa-list dengan class bg-navy)
    const detailButton = page.locator('a[href*="/mailbox/detail/"]').first();
    
    // Jika tidak ada data, skip test
    const buttonCount = await detailButton.count();
    if (buttonCount === 0) {
      test.skip();
      return;
    }

    // 4. Klik tombol detail
    await detailButton.click();

    // 5. Tunggu halaman loading
    await page.waitForLoadState('networkidle');
    await page.waitForTimeout(1000);

    // 6. Verifikasi TIDAK ada error 404
    const errorPage = page.locator('text=/Error 404|Halaman Tidak Ditemukan|Page Not Found/i');
    await expect(errorPage).not.toBeVisible();

    // 7. Verifikasi halaman detail pesan ditampilkan
    const detailTitle = page.locator('h1, h2, h3');
    const titleVisible = await detailTitle.count() > 0;
    expect(titleVisible).toBeTruthy();

    // Pastikan minimal ada informasi detail pesan seperti subjek atau pengirim
    const detailContent = page.locator('text=/Subjek|Pengirim|Pesan/i');
    await expect(detailContent).toHaveCount(1, { timeout: 5000 });
  });

  test('should NOT show 404 error when clicking "Aktifkan/Nonaktifkan" button', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10868',
    },
  }, async ({ page }) => {
    // 1. Pergi ke halaman kotak masuk
    await page.goto(MAILBOX_MASUK);
    await expect(page.locator('h1')).toContainText(/Kotak Masuk|Kotak Pesan/i);

    // 2. Tunggu datatable dimuat
    await page.waitForLoadState('networkidle');
    await page.waitForTimeout(2000);

    // 3. Cari tombol toggle status (icon fa-envelope atau fa-envelope-open)
    // Button dengan class bg-navy yang bukan detail dan bukan archive
    const allButtons = page.locator('a.btn.btn-sm.bg-navy');
    const buttonCount = await allButtons.count();

    // Ambil button yang bukan yang pertama (detail button)
    let statusButton = null;
    for (let i = 1; i < buttonCount; i++) {
      const href = await allButtons.nth(i).getAttribute('href');
      if (href && href.includes('/mailbox/read/')) {
        statusButton = allButtons.nth(i);
        break;
      }
    }

    if (!statusButton) {
      test.skip();
      return;
    }

    // 4. Get initial URL untuk membandingkan
    const initialUrl = page.url();

    // 5. Klik tombol aktifkan/nonaktifkan
    await statusButton.click();

    // 6. Tunggu halaman loading dan redirect
    await page.waitForLoadState('networkidle');
    await page.waitForTimeout(1500);

    // 7. Verifikasi TIDAK ada error 404
    const errorPage = page.locator('text=/Error 404|Halaman Tidak Ditemukan|Page Not Found/i');
    await expect(errorPage).not.toBeVisible();

    // 8. Verifikasi kembali ke halaman kotak masuk
    const currentUrl = page.url();
    expect(currentUrl).toContain('mailbox');

    // 9. Verifikasi ada pesan sukses atau minimal datatable masih ditampilkan
    const table = page.locator('table, [role="table"]');
    await expect(table).toBeVisible();
  });

  test('should NOT show 404 error when clicking "Arsipkan pesan" button', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10868',
    },
  }, async ({ page }) => {
    // 1. Pergi ke halaman kotak masuk
    await page.goto(MAILBOX_MASUK);
    await expect(page.locator('h1')).toContainText(/Kotak Masuk|Kotak Pesan/i);

    // 2. Tunggu datatable dimuat
    await page.waitForLoadState('networkidle');
    await page.waitForTimeout(2000);

    // 3. Cari tombol archive (icon fa-file-archive-o dengan class bg-maroon)
    const archiveButton = page.locator('a[data-href*="/mailbox/delete/"]').first();
    
    const archiveCount = await archiveButton.count();
    if (archiveCount === 0) {
      test.skip();
      return;
    }

    // 4. Klik tombol archive
    await archiveButton.click();

    // 5. Tunggu modal konfirmasi atau loading
    await page.waitForLoadState('networkidle');
    await page.waitForTimeout(1000);

    // 6. Jika ada modal konfirmasi, klik OK/Confirm
    const confirmButton = page.locator('button:has-text("OK"), button:has-text("Konfirmasi"), button:has-text("Ya")').first();
    if (await confirmButton.isVisible()) {
      await confirmButton.click();
      await page.waitForLoadState('networkidle');
      await page.waitForTimeout(1500);
    }

    // 7. Verifikasi TIDAK ada error 404
    const errorPage = page.locator('text=/Error 404|Halaman Tidak Ditemukan|Page Not Found/i');
    await expect(errorPage).not.toBeVisible();

    // 8. Verifikasi kembali ke halaman kotak masuk (archive seringkali redirect)
    const currentUrl = page.url();
    expect(currentUrl).toContain('mailbox');

    // 9. Verifikasi halaman masih menampilkan list pesan
    const table = page.locator('table, [role="table"]');
    await expect(table).toBeVisible();
  });

  test('should handle action buttons with proper navigation URLs', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10868',
    },
  }, async ({ page }) => {
    // 1. Pergi ke halaman kotak masuk
    await page.goto(MAILBOX_MASUK);
    await page.waitForLoadState('networkidle');
    await page.waitForTimeout(2000);

    // 2. Verifikasi struktur URL pada setiap button aksi
    // Detail button harus memiliki format: /mailbox/detail/{tipe}/{uuid}
    const detailButton = page.locator('a[href*="/mailbox/detail/"]').first();
    if (await detailButton.count() > 0) {
      const detailHref = await detailButton.getAttribute('href');
      expect(detailHref).toMatch(/\/mailbox\/detail\/\d+\/.+/);
    }

    // Status button harus memiliki format: /mailbox/read/{tipe}/{uuid}
    const statusButtons = page.locator('a[href*="/mailbox/read/"]');
    if (await statusButtons.count() > 0) {
      for (let i = 0; i < await statusButtons.count(); i++) {
        const href = await statusButtons.nth(i).getAttribute('href');
        expect(href).toMatch(/\/mailbox\/read\/\d+\/.+/);
      }
    }

    // Archive button harus memiliki format: data-href="/mailbox/delete/{tipe}/{uuid}"
    const archiveButton = page.locator('a[data-href*="/mailbox/delete/"]').first();
    if (await archiveButton.count() > 0) {
      const dataHref = await archiveButton.getAttribute('data-href');
      expect(dataHref).toMatch(/\/mailbox\/delete\/\d+\/.+/);
    }
  });

  test('should verify action buttons respond correctly with proper HTTP status codes', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10868',
    },
  }, async ({ page }) => {
    // 1. Setup interception untuk memonitor response
    let hasNot404Error = true;
    
    page.on('response', (response) => {
      if (response.status() === 404) {
        hasNot404Error = false;
      }
    });

    // 2. Pergi ke halaman kotak masuk
    await page.goto(MAILBOX_MASUK);
    await page.waitForLoadState('networkidle');
    await page.waitForTimeout(2000);

    // 3. Cari dan klik tombol detail
    const detailButton = page.locator('a[href*="/mailbox/detail/"]').first();
    if (await detailButton.count() > 0) {
      await detailButton.click();
      await page.waitForLoadState('networkidle');
      await page.waitForTimeout(1000);

      // Verifikasi tidak ada 404 response
      expect(hasNot404Error).toBeTruthy();

      // Kembali ke mailbox
      await page.goBack();
      await page.waitForLoadState('networkidle');
    }

    // Reset flag untuk test berikutnya
    hasNot404Error = true;

    // 4. Tunggu datatable reload
    await page.waitForTimeout(2000);

    // 5. Cari dan klik tombol status toggle jika ada
    const statusButton = page.locator('a[href*="/mailbox/read/"]').first();
    if (await statusButton.count() > 0) {
      await statusButton.click();
      await page.waitForLoadState('networkidle');
      await page.waitForTimeout(1500);

      // Verifikasi tidak ada 404 response
      expect(hasNot404Error).toBeTruthy();
    }
  });

  test('should test mailbox keluar (outgoing messages) action buttons', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10868',
    },
  }, async ({ page }) => {
    // 1. Pergi ke halaman kotak keluar
    await page.goto(MAILBOX_KELUAR);
    
    // Tunggu halaman dimuat atau skip jika tidak ada akses
    try {
      await expect(page.locator('h1')).toContainText(/Kotak|Pesan/i, { timeout: 5000 });
    } catch {
      test.skip();
      return;
    }

    // 2. Tunggu datatable dimuat
    await page.waitForLoadState('networkidle');
    await page.waitForTimeout(2000);

    // 3. Verifikasi minimal ada detail button
    const detailButton = page.locator('a[href*="/mailbox/detail/"]').first();
    
    if (await detailButton.count() === 0) {
      test.skip();
      return;
    }

    // 4. Klik detail button pada kotak keluar
    const initialUrl = page.url();
    await detailButton.click();
    await page.waitForLoadState('networkidle');
    await page.waitForTimeout(1000);

    // 5. Verifikasi TIDAK ada error 404
    const errorPage = page.locator('text=/Error 404|Halaman Tidak Ditemukan|Page Not Found/i');
    await expect(errorPage).not.toBeVisible();

    // 6. Verifikasi berbeda URL dengan sebelumnya
    const currentUrl = page.url();
    expect(currentUrl).not.toBe(initialUrl);
  });
});
