import { test, expect } from '@playwright/test';
import https from 'https';
import path from 'path';
import fs from 'fs';
import { AddressInfo } from 'net';

/**
 * Skenario 3 (Fase 2, OpenSID/OpenSID#11931): pasang satu modul lewat alur
 * bootstrap (mock server lokal utk uji hermetik, tak bergantung jaringan/
 * Layanan sungguhan -- endpoint Layanan asli sudah diverifikasi manual
 * terpisah, lihat Fase 3.5 di issue) -> aplikasi tetap boot & berfungsi
 * setelahnya, menu admin bertambah. Skenario penentu tujuan #2 refaktor
 * ("Umum bisa memasang modul/tema berbayar dari Layanan").
 *
 * SEJARAH (Fase 2 -> Fase 3.5, lihat OpenSID/OpenSID#11931 utk detail penuh):
 * - Fase 2: skenario ini `test.skip()` -- alur OTOMATIS
 *   `SettingAplikasiObserver::saved() -> ModuleManager::jalankanBootstrap()`
 *   belum ada di sumber v2601-v2609, dan command test-support
 *   `modul:pasang-uji` belum dibangun.
 * - Fase 3.5: `modul:pasang-uji` dibangun (`pipeline/additions/app/Console/
 *   Commands/PasangModulUji.php`) + diverifikasi BOOT SUNGGUHAN lawan
 *   `layanan.test` asli (bukan mock) berhasil memasang Anjungan. TAPI mock
 *   server lokal di skenario INI awalnya HTTP polos -- `LayananHttpSource`
 *   di kohort ini menegakkan HTTPS+host-pinning TANPA bypass dev-mode
 *   (beda dari Premium saat ini yang punya bypass utk locator kosong), jadi
 *   mock HTTP polos TAK PERNAH bisa lolos seperti desain awal. Diperbaiki
 *   dgn mock server HTTPS bersertifikat self-signed (dibuat globalSetup.ts,
 *   `ensureMockServerCert()`) + `-d curl.cainfo=`/`-d openssl.cafile=` pada
 *   perintah `php -S` webServer (lihat playwright.smoke-2701.config.ts) --
 *   PHP curl di build ini TIDAK menghormati env var `CURL_CA_BUNDLE`
 *   (diverifikasi empiris), jadi override WAJIB lewat ini kedua ini.
 *
 * ModuleSource (LayananHttpSource asli) diarahkan ke mock server lokal
 * lewat `--url-penyedia` (dibuat di dalam test ini) yang menyajikan ZIP
 * modul fixture (`tests/playwright/storage/modules/contoh-modul.zip`) --
 * jadi kode HTTP-download+extract+migrasi yang SESUNGGUHNYA yang diuji,
 * bukan tiruan.
 */
test.describe('Alur pasang add-on dari Layanan', () => {
  test('modul terpasang lewat LayananHttpSource, aplikasi tetap boot, menu admin bertambah', async ({ page }) => {
    const fixtureDir = path.resolve(__dirname, '../../storage/modules');
    const zipPath = path.join(fixtureDir, 'contoh-modul.zip');
    const certPath = path.join(fixtureDir, 'mock-server-cert.pem');
    const keyPath = path.join(fixtureDir, 'mock-server-key.pem');

    let port = 0;
    const server = https.createServer(
      { cert: fs.readFileSync(certPath), key: fs.readFileSync(keyPath) },
      (req, res) => {
        if (req.url === '/api/v1/token/bootstrap') {
          res.writeHead(200, { 'Content-Type': 'application/json' });
          res.end(JSON.stringify({
            modules: [{ name: 'ContohModul', url: `https://127.0.0.1:${port}/contoh-modul.zip` }],
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
      }
    );
    await new Promise<void>((resolve) => server.listen(0, '127.0.0.1', resolve));
    port = (server.address() as AddressInfo).port;

    try {
      // Panggil command test-support yang men-set ModuleSource ke mock server
      // lokal di atas lalu memanggil ModuleManager::installFromSource('ContohModul').
      const response = await page.request.post('/playwright/artisan', {
        data: {
          command: 'modul:pasang-uji',
          parameters: { modul: 'ContohModul', '--url-penyedia': `https://127.0.0.1:${port}` },
        },
      });
      expect(response.ok()).toBeTruthy();
      const body = await response.json();
      expect(String(body)).toContain('berhasil dipasang');

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
