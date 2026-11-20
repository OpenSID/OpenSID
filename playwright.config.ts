import { defineConfig, devices } from '@playwright/test';

/**
 * Read environment variables from file.
 * https://github.com/motdotla/dotenv
 */
import dotenv from 'dotenv';
import path from 'path';
dotenv.config({ path: path.resolve(__dirname, '.e2e.env') });

/**
 * APP_KEY fixture tes — HARUS sama dengan `config.app_key` yang diset `seed.sql`
 * dan dengan `desa/app_key` di `desa.zip`. `get_app_key()` memprioritaskan
 * `env('APP_KEY')`, jadi tanpa ini webServer memakai APP_KEY .env (produksi) yang
 * berbeda → MY_Controller menganggap app_key tak cocok → redirect koneksi_database.
 * Bisa di-override lewat env (mis. di workflow CI).
 */
const TEST_APP_KEY = process.env.APP_KEY || 'base64:/2PUGKKo+G4EqZ84quM4sVXS2oogaxOKjW136Rl1pHY=';

/**
 * See https://playwright.dev/docs/test-configuration.
 */
export default defineConfig({
  globalSetup: './tests/playwright/globalSetup.ts',
  testDir: './tests/playwright',
  testIgnore: '**/akan-dihapus/**',
  /* Run tests in files in parallel */
  fullyParallel: true,
  /* Fail the build on CI if you accidentally left test.only in the source code. */
  forbidOnly: !!process.env.CI,
  /* Retry on CI only */
  retries: process.env.CI ? 2 : 0,
  /*
   * Fix 4: Cap Playwright workers at 4 locally (safe for PHP built-in server).
   * PHP_CLI_SERVER_WORKERS is set to 3× this value (12) so the PHP pool never
   * exhausts under full parallel load. Override via PLAYWRIGHT_WORKERS env var
   * when investigating specific worker counts.
   * Note: full nginx/FPM migration (Herd pool with opensid_playwright env var) is
   * the ideal long-term fix but requires a dedicated Herd FPM pool config — see docs.
   */
  workers: process.env.CI ? 1 : parseInt(process.env.PLAYWRIGHT_WORKERS || '4', 10),
  /* Reporter to use. See https://playwright.dev/docs/test-reporters */
  reporter: [
    ['html', { outputFolder: './tests/playwright/report' }],
    ['json', { outputFile: './tests/playwright/report/test-results.json' }]
  ],
  /* Shared settings for all the projects below. See https://playwright.dev/docs/api/class-testoptions. */
  use: {
    /* Base URL to use in actions like `await page.goto('/')`. */
    baseURL: process.env.PLAYWRIGHT_BASE_URL,
    ignoreHTTPSErrors: true,

    /* Collect trace when retrying the failed test. See https://playwright.dev/docs/trace-viewer */
    trace: 'on-first-retry',
    screenshot: 'only-on-failure',
    video: 'retain-on-failure',
    /* Fixed viewport: smaller surface = less rendering work, still wide enough for all layouts */
    viewport: { width: 1280, height: 900 },
    /* Fix 1: disable GPU to avoid CVDisplayLink exhaustion on macOS under parallel load */
    launchOptions: {
      args: [
        '--disable-gpu',
        '--disable-dev-shm-usage',
        '--no-sandbox',
        '--disable-software-rasterizer',
      ],
    },
  },

  /* Configure projects for major browsers */
  projects: [
    {
      name: 'setup',
      testMatch: /.*\.setup\.ts/,
    },
    {
      name: 'chromium',
      use: {
        ...devices['Desktop Chrome'],
        deviceScaleFactor: undefined,
        viewport: { width: 1280, height: 900 },
      },
      dependencies: ['setup'],
    },
  ],

  /* Run your local dev server before starting the tests */
  webServer: {
    command: process.platform === 'win32'
      ? `set APP_ENV=testing && set CI_ENV=testing && set APP_KEY=${TEST_APP_KEY} && set DB_CONNECTION=default && set DEBUGBAR_ENABLED=false && set OPENSID_DB_DATABASE=${process.env.PLAYWRIGHT_DB_DATABASE || process.env.DB_DATABASE || 'opensid_playwright'} && php -S 127.0.0.1:8000 > nul 2>&1`
      /* Fix 4: PHP workers = 3× Playwright workers so requests never queue.
         APP_ENV=testing + CI_ENV=testing → lingkungan "testing" yang konsisten
         (sama dengan PHPUnit/Larastan), bukan "development". Gerbang Laravel
         (langganan/token, pengumuman) dilewati via app()->environment(['testing']);
         gerbang sandi (force_change_password) dihindari karena sandi admin seed
         (Admin$123) sudah memenuhi syaratSandi — jadi tak perlu ENVIRONMENT='development'.
         APP_KEY menyamakan kunci dengan config.app_key DB tes (get_app_key()
         memprioritaskan env('APP_KEY')) agar tidak ter-redirect ke koneksi_database.
         DB_CONNECTION=default → koneksi default bernama "default" (config DB pakai
         env('DB_CONNECTION','mysql')); Database.php memakai getConnections()['default'],
         tanpa ini koneksi bernama "mysql" → null → 500.
         DEBUGBAR_ENABLED=false mematikan Debugbar via env (bukan hack runtime). */
      : `PHP_CLI_SERVER_WORKERS=${3 * parseInt(process.env.PLAYWRIGHT_WORKERS || '4', 10)} APP_ENV=testing CI_ENV=testing APP_KEY=${TEST_APP_KEY} DB_CONNECTION=default DEBUGBAR_ENABLED=false OPENSID_DB_DATABASE=${process.env.PLAYWRIGHT_DB_DATABASE || process.env.DB_DATABASE || 'opensid_playwright'} php -S 127.0.0.1:8000 > /dev/null 2>&1`,
    url: 'http://127.0.0.1:8000/siteman',
    /* Fix 2: never reuse an existing server — memastikan env webServer (APP_KEY,
       DEBUGBAR_ENABLED, PHP_CLI_SERVER_WORKERS) selalu ter-set */
    reuseExistingServer: !process.env.CI,
  },
});
