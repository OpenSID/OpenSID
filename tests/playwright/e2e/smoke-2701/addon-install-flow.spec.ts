import { test, expect } from '@playwright/test';
import http from 'http';
import path from 'path';
import fs from 'fs';
import { AddressInfo } from 'net';

/**
 * Skenario 3 (Fase 2, OpenSID/OpenSID#11931): pasang satu modul lewat alur
 * bootstrap (emulator lokal utk uji, endpoint Layanan asli sudah dicakup
 * lewat LayananHttpSource) -> aplikasi tetap boot & berfungsi setelahnya,
 * menu admin bertambah. Skenario penentu tujuan #2 refaktor ("Umum bisa
 * memasang modul/tema berbayar dari Layanan").
 *
 * CATATAN PENTING (ditemukan saat menulis skenario ini): pada sumber
 * v2601-v2609 yang dipakai pipeline ini, jalur OTOMATIS
 * `SettingAplikasiObserver::saved() -> ModuleManager::jalankanBootstrap()`
 * yang didokumentasikan di CLAUDE.md Premium BELUM ADA -- `SettingAplikasiObserver`
 * sendiri adalah fitur pasca-refaktor (lihat OpenSID/OpenSID#11931 Fase 1,
 * catatan pada AppServiceProvider::boot()). `pipeline/additions/` menambahkan
 * KELAS layanannya (ModuleManager/LayananHttpSource/ModuleSource) tapi TIDAK
 * ADA titik panggil otomatis apa pun yang memicunya di v2601-v2609 -- tidak
 * ada UI admin "Paket Tambahan", dan `Install_modul::pasang()` CI3 yang ada
 * adalah mekanisme LAMA yang mengasumsikan folder modul sudah ada di disk
 * (bukan alur download dari Layanan).
 *
 * Skenario ini karena itu menguji ModuleManager::pasang() SECARA LANGSUNG
 * (dipanggil lewat command artisan `modul:pasang-uji`, ditambahkan sebagai
 * bagian dari harness ini -- lihat tests/playwright/storage/support/
 * ModulPasangUjiCommand.php) alih-alih lewat UI/observer yang belum ada.
 * ModuleSource (LayananHttpSource asli) diarahkan ke server HTTP lokal
 * (dibuat di dalam test ini, bukan `layanan.opendesa.id` sungguhan) yang
 * menyajikan ZIP modul fixture -- jadi kode HTTP-download+extract yang
 * SESUNGGUHNYA yang diuji, bukan tiruan.
 *
 * TODO (belum diverifikasi live per Fase 2): command `modul:pasang-uji` di
 * atas belum ditambahkan ke tree Umum -- perlu PR kecil terpisah men-support
 * override ModuleSource ke URL lokal via env var (mis. `BURSA_URL_PENYEDIA`
 * dibaca ulang oleh ModuleSource, bukan cuma config('bursa')). Jalankan
 * `PLAYWRIGHT_RESET_DB=true npx playwright test --config=playwright.smoke-2701.config.ts
 * addon-install-flow` secara lokal setelah wiring itu ada, sebelum
 * mengandalkan skenario ini di CI.
 */
test.describe('Alur pasang add-on dari Layanan', () => {
  test('modul terpasang lewat LayananHttpSource, aplikasi tetap boot, menu admin bertambah', async ({ page }) => {
    const fixtureDir = path.resolve(__dirname, '../../storage/modules');
    const zipPath = path.join(fixtureDir, 'contoh-modul.zip');
    test.skip(!fs.existsSync(zipPath), 'Fixture contoh-modul.zip belum ada -- lihat TODO di berkas ini.');

    const server = http.createServer((req, res) => {
      if (req.url === '/api/v1/token/bootstrap') {
        res.writeHead(200, { 'Content-Type': 'application/json' });
        res.end(JSON.stringify({
          modules: [{ name: 'ContohModul', url: `http://127.0.0.1:${port}/contoh-modul.zip` }],
        }));
        return;
      }
      if (req.url === '/contoh-modul.zip') {
        res.writeHead(200, { 'Content-Type': 'application/zip' });
        fs.createReadStream(zipPath).pipe(res);
        return;
      }
      res.writeHead(404);
      res.end();
    });
    await new Promise<void>((resolve) => server.listen(0, '127.0.0.1', resolve));
    const port = (server.address() as AddressInfo).port;

    try {
      // Panggil command test-support yang men-set ModuleSource ke server lokal
      // di atas lalu memanggil ModuleManager::pasang('ContohModul').
      const response = await page.request.post('/playwright/artisan', {
        data: {
          command: 'modul:pasang-uji',
          parameters: { modul: 'ContohModul', '--url-penyedia': `http://127.0.0.1:${port}` },
        },
      });
      expect(response.ok()).toBeTruthy();

      // Aplikasi tetap boot setelah pemasangan.
      const home = await page.goto('/');
      expect(home?.status()).toBeLessThan(400);

      // Menu admin bertambah entri modul yang baru dipasang.
      await page.goto('/index.php/siteman');
      await page.getByPlaceholder('Nama pengguna').fill(process.env.PLAYWRIGHT_AUTH_USERNAME || 'admin');
      await page.getByPlaceholder('Kata sandi').fill(process.env.PLAYWRIGHT_AUTH_PASSWORD || 'Admin$123');
      await page.getByRole('button', { name: 'Masuk' }).first().click();
      await expect(page.locator('body')).toContainText('Contoh Modul');
    } finally {
      server.close();
    }
  });
});
