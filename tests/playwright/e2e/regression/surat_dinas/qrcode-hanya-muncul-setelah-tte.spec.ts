// Issue: #11156 — Fix: QR code hanya muncul setelah TTE
// https://github.com/OpenSID/OpenSID/issues/11156
import { test, expect } from '../../../utils/regressionTest';


test.describe('Fix: QR Code hanya muncul setelah TTE (issue #11156)', () => {
  test('verifikasi tombol QR Code tidak muncul di arsip surat keluar sebelum TTE', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11156',
    },
  }, async ({ page }) => {
    test.setTimeout(30000);

    // Navigasi ke halaman arsip surat keluar
    await page.goto('keluar');
    await page.waitForLoadState('networkidle');

    // Tunggu tabel muncul
    await page.waitForSelector('table#tabeldata', { timeout: 10000 });

    // Cari tombol QR Code di baris surat yang belum TTE
    const qrButtons = await page.locator('a.viewQR, button.viewQR').count();

    // Verifikasi tidak ada tombol QR Code yang terlihat
    // (Jika ada button dengan kelas viewQR, itu berarti masih ada bug)
    expect(qrButtons).toBe(0);
  });

  test('verifikasi tombol QR Code tidak muncul di arsip surat dinas masuk sebelum TTE', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11156',
    },
  }, async ({ page }) => {
    test.setTimeout(30000);

    // Navigasi ke halaman surat dinas masuk
    await page.goto('surat_dinas_arsip/masuk');
    await page.waitForLoadState('networkidle');

    // Tunggu tabel muncul
    await page.waitForSelector('table#tabeldata', { timeout: 10000 });

    // Cari tombol QR Code di baris surat
    const qrButtons = await page.locator('a[title="QR Code"]').count();

    // Verifikasi tidak ada tombol QR Code pada surat yang belum TTE
    expect(qrButtons).toBe(0);
  });

  test('verifikasi struktur tombol aksi pada arsip surat keluar', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11156',
    },
  }, async ({ page }) => {
    test.setTimeout(30000);

    // Navigasi ke arsip surat keluar
    await page.goto('keluar');
    await page.waitForLoadState('networkidle');

    // Tunggu tabel dan baris data muncul (DataTables AJAX)
    await page.waitForSelector('table#tabeldata', { timeout: 10000 });
    await page.waitForSelector('table#tabeldata tbody tr', { timeout: 15000 });

    // Periksa sel aksi pada baris pertama
    const firstActionCell = page.locator('table#tabeldata tbody tr:first-child td.aksi');
    await expect(firstActionCell).toBeVisible();

    // Hitung jumlah button di sel aksi
    const actionButtons = await firstActionCell.locator('button, a').count();

    // Pastikan ada button/link di aksi
    expect(actionButtons).toBeGreaterThan(0);

    // Verifikasi tidak ada button QR Code dengan class viewQR
    const qrButton = await firstActionCell.locator('.viewQR').count();
    expect(qrButton).toBe(0);
  });

  test('verifikasi struktur tombol aksi pada arsip surat dinas', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11156',
    },
  }, async ({ page }) => {
    test.setTimeout(30000);

    // Navigasi ke surat dinas arsip masuk
    await page.goto('surat_dinas_arsip/masuk');
    await page.waitForLoadState('networkidle');

    // Tunggu tabel dan baris data muncul (DataTables AJAX)
    await page.waitForSelector('table#tabeldata', { timeout: 10000 });
    await page.waitForSelector('table#tabeldata tbody tr', { timeout: 15000 });

    // Periksa sel aksi pada baris pertama
    const firstActionCell = page.locator('table#tabeldata tbody tr:first-child td.aksi');
    await expect(firstActionCell).toBeVisible();

    // Hitung jumlah button di sel aksi
    const actionButtons = await firstActionCell.locator('button, a').count();

    // Pastikan ada button/link di aksi
    expect(actionButtons).toBeGreaterThan(0);

    // Verifikasi tidak ada link dengan title "QR Code"
    const qrLink = await firstActionCell.locator('a[title="QR Code"]').count();
    expect(qrLink).toBe(0);
  });

  test('verifikasi elemen QR Code tidak ada di DOM sebelum TTE', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11156',
    },
  }, async ({ page }) => {
    test.setTimeout(30000);

    // Navigasi ke arsip surat keluar
    await page.goto('keluar');
    await page.waitForLoadState('networkidle');

    // Tunggu tabel muncul
    await page.waitForSelector('table#tabeldata', { timeout: 10000 });

    // Cari tombol QR Code langsung di dalam tabel arsip (bukan navigasi sidebar)
    // Navigasi sidebar punya item "QR Code" di menu Pengaturan — harus dikecualikan
    const qrButtonsInTable = await page.locator('table#tabeldata tbody').locator('a.viewQR, button.viewQR, a[title="QR Code"]').count();

    // Verifikasi tidak ada tombol QR Code di baris tabel sebelum TTE
    expect(qrButtonsInTable).toBe(0);
  });
});
