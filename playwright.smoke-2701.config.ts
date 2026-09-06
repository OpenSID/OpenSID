import { defineConfig, devices } from '@playwright/test';

/**
 * Config KHUSUS untuk suite smoke rilis Umum 2701+ (bukan playwright.config.ts
 * biasa, yang menjalankan suite regresi lama terhadap fixture desa lama).
 *
 * Kenapa terpisah (sama alasannya dengan playwright.aktivasi.config.ts di
 * Premium): fixture DB/desa di sini (schema-2701.sql/seed-2701.sql/
 * desa-2701.zip) khusus untuk tree hasil pipeline/pipeline.sh (murni-OSS,
 * tanpa Anjungan/Pelanggan/BukuTamu/DTSEN) — beda sama sekali dari desa.zip
 * lama yang dipakai suite regresi (tests/playwright/e2e/bugs,features).
 * Menjalankan lewat playwright.config.ts biasa akan menimpa/bentrok dengan
 * fixture desa lama.
 *
 * Lihat OpenSID/OpenSID#11931 Fase 2.
 */
export default defineConfig({
  testDir: './tests/playwright/e2e/smoke-2701',
  fullyParallel: false,
  forbidOnly: !!process.env.CI,
  retries: process.env.CI ? 1 : 0,
  workers: 1,
  reporter: [
    ['html', { outputFolder: './tests/playwright/report-smoke-2701' }],
    ['json', { outputFile: './tests/playwright/report-smoke-2701/test-results.json' }],
    ['line'],
  ],
  globalSetup: './tests/playwright/e2e/smoke-2701/globalSetup.ts',
  use: {
    baseURL: process.env.PLAYWRIGHT_BASE_URL || 'http://127.0.0.1:8010',
    trace: 'on-first-retry',
    screenshot: 'only-on-failure',
    video: 'retain-on-failure',
  },
  projects: [
    {
      name: 'chromium',
      use: { ...devices['Desktop Chrome'] },
    },
  ],

  /*
   * Server nyata (bukan tiruan) lewat `php -S` + router-testing.php. Router
   * dibutuhkan krn dua alasan (lihat komentar di router-testing.php):
   *  1. php -S TIDAK menyalin env var shell ke $_SERVER/$_ENV per-request —
   *     hanya getenv() yang reflect env asli. index.php Umum me-resolve
   *     ENVIRONMENT dari $_ENV['CI_ENV']/$_SERVER['CI_ENV'] LANGSUNG (sebelum
   *     Laravel/Dotenv sempat load) — tanpa jembatan ini ENVIRONMENT selalu
   *     "production", captcha admin login jadi wajib & tak bisa diotomasi.
   *  2. Static asset (css/js, termasuk anti-csrf.js yang mengisi token CSRF
   *     form login) harus dilayani APA ADANYA, bukan lewat dispatch CI3.
   */
  webServer: {
    // -d curl.cainfo: OpenSID/OpenSID#11931 Fase 3.5 -- addon-install-flow
    // memakai mock server HTTPS self-signed lokal (LayananHttpSource menegakkan
    // HTTPS, tak ada bypass dev-mode di kohort ini). PHP curl TIDAK menghormati
    // env var CURL_CA_BUNDLE di build ini (diverifikasi empiris) -- override
    // ini WAJIB lewat ini, bukan env var. Sertifikat dibuat globalSetup.ts
    // SEBELUM webServer start (path tetap, lihat ensureMockServerCert()).
    command: `CI_ENV=testing APP_ENV=testing DB_CONNECTION=default DB_HOST=${process.env.PLAYWRIGHT_DB_HOST || '127.0.0.1'} DB_DATABASE=${process.env.PLAYWRIGHT_DB_DATABASE || 'opensid_playwright_umum'} DB_USERNAME=${process.env.PLAYWRIGHT_DB_USERNAME || 'root'} DB_PASSWORD=${process.env.PLAYWRIGHT_DB_PASSWORD || ''} PLAYWRIGHT_BASE_URL=${process.env.PLAYWRIGHT_BASE_URL || 'http://127.0.0.1:8010'} php -d curl.cainfo=tests/playwright/storage/modules/mock-server-cert.pem -d openssl.cafile=tests/playwright/storage/modules/mock-server-cert.pem -S 127.0.0.1:8010 router-testing.php`,
    url: 'http://127.0.0.1:8010',
    reuseExistingServer: !process.env.CI,
    timeout: 30_000,
  },
});
