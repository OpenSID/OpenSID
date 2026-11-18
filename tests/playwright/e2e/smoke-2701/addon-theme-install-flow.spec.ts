import { test, expect } from '@playwright/test';
import https from 'https';
import path from 'path';
import fs from 'fs';
import { AddressInfo } from 'net';

/**
 * Skenario 4 (OpenSID/OpenSID#11931 Fase 3.5, "Langkah — Jalur Tema"): pasang +
 * aktifkan satu TEMA berbayar lewat alur bursa tema (mock server HTTPS lokal utk
 * uji hermetik, tak bergantung jaringan/Layanan sungguhan — endpoint Layanan
 * asli `GET /api/v1/themes` + unduh ZIP tema sudah diverifikasi manual terpisah,
 * lihat Fase 3.5 di issue) -> aplikasi tetap boot & tema aktif berpindah.
 *
 * Padanan tema untuk `addon-install-flow.spec.ts` (jalur modul). Sumber kelas
 * jalur tema BEDA dari modul (issue Fase 3.5): `App\Actions\Theme\ActivateTheme`
 * (di-overlay `pipeline/additions/` — TAK ADA di sumber v2601–v2604) dipicu dari
 * `donjo-app/controllers/Theme.php::aktifkan()` (di-wire pipeline utk v2601–v2604,
 * lihat `pipeline/helpers/patch-boot-tentacles-umum.php` item 12) dan command
 * test-support `tema:pasang-uji`
 * (`pipeline/additions/app/Console/Commands/PasangTemaUji.php`).
 *
 * `tema:pasang-uji` melakukan alur `Theme.php::unduh()` + `aktifkan()` core
 * dirangkai: resolusi katalog (`GET /api/v1/themes`) -> unduh ZIP terautentikasi
 * (HTTPS + host-pinning) -> ekstrak ke `desa/themes/` -> `theme_scan()` ->
 * `ActivateTheme::handle()`. Mock server diarahkan lewat `--url-penyedia`.
 *
 * TLS: mock server memakai sertifikat self-signed yang SAMA dengan
 * `addon-install-flow` (dibuat `globalSetup.ts` `ensureMockServerCert()`), dan
 * `php -S` webServer sudah diberi `-d curl.cainfo=` menunjuk sertifikat itu
 * (lihat playwright.smoke-2701.config.ts) — PHP curl di build ini TIDAK
 * menghormati env var `CURL_CA_BUNDLE` (diverifikasi empiris).
 *
 * Assertion memakai state DB (`theme.status`) + boot, BUKAN stdout command:
 * `ActivateTheme::handle()` memanggil `Artisan::call('view:clear')` bersarang,
 * yang menimpa buffer output `Artisan::output()` milik bridge — jadi baris
 * `info()` command tak sampai ke respons. State DB adalah bukti yang lebih kuat
 * (membuktikan aktivasi, bukan cuma cetakan).
 */
test.describe('Alur pasang add-on tema dari Layanan', () => {
  test('tema terpasang lewat bursa tema, aplikasi tetap boot, tema aktif berpindah', async ({ page }) => {
    const fixtureDir = path.resolve(__dirname, '../../storage/modules');
    const zipPath = path.join(fixtureDir, 'contoh-tema.zip');
    const certPath = path.join(fixtureDir, 'mock-server-cert.pem');
    const keyPath = path.join(fixtureDir, 'mock-server-key.pem');

    let port = 0;
    const server = https.createServer(
      { cert: fs.readFileSync(certPath), key: fs.readFileSync(keyPath) },
      (req, res) => {
        const url = (req.url || '').split('?')[0];
        if (url === '/api/v1/themes') {
          res.writeHead(200, { 'Content-Type': 'application/json' });
          res.end(JSON.stringify({
            data: [{
              name: 'Contoh Tema',
              alias: 'contoh-tema',
              version: '1.0.0',
              description: 'Tema fixture uji Playwright',
              url: `https://127.0.0.1:${port}/contoh-tema.zip`,
            }],
          }));
          return;
        }
        if (url === '/contoh-tema.zip') {
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
      const response = await page.request.post('/playwright/artisan', {
        data: {
          command: 'tema:pasang-uji',
          parameters: { tema: 'Contoh Tema', '--url-penyedia': `https://127.0.0.1:${port}` },
        },
      });
      expect(response.ok()).toBeTruthy();

      // Baris `theme` untuk tema yang baru dipasang ADA & berstatus aktif;
      // tema lama tidak lagi aktif.
      const aktif = await page.request.post('/playwright/select', {
        data: { query: 'SELECT slug FROM theme WHERE status = 1' },
      });
      expect(aktif.ok()).toBeTruthy();
      const rows: { slug: string }[] = await aktif.json();
      expect(rows.map((r) => r.slug)).toEqual(['desa-contoh-tema']);

      // Aplikasi tetap boot setelah pemasangan + aktivasi tema — beranda publik
      // (dirender tema baru) DAN halaman admin.
      const home = await page.goto('/');
      expect(home?.status()).toBeLessThan(400);

      const admin = await page.goto('/index.php/siteman');
      expect(admin?.status()).toBeLessThan(400);
    } finally {
      server.close();
    }
  });
});
