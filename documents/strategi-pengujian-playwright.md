# Strategi Pengujian Playwright OpenSID

Dokumen ini menjelaskan **filosofi, struktur, dan pola** pengujian end-to-end (E2E) Playwright OpenSID. Untuk cara menjalankan secara lokal, lihat [panduan-menjalankan-tes-playwright-lokal.md](panduan-menjalankan-tes-playwright-lokal.md).

---

## 1. Tujuan & Filosofi

- **Mencegah regresi** pada alur fungsional inti OpenSID lewat pengujian terhadap aplikasi yang benar-benar berjalan (browser + PHP + MySQL).
- **Deterministik**: setiap kegagalan harus punya penyebab nyata, bukan artefak timing/paralelisme. Tidak ada "hijau palsu".
- **Terisolasi**: tes tidak boleh saling merusak prasyarat; data berasal dari seed yang dikontrol, bukan database produksi.
- **Cepat memberi sinyal**: gate cepat (smoke) untuk setiap PR; regresi penuh untuk nightly/merge.

---

## 2. Struktur Folder

```
tests/playwright/
├── auth.setup.ts            # proyek "setup": login admin → storageState + auth per-worker
├── globalSetup.ts           # reset DB (schema+seed+desa+opensid:setup) saat CI/PLAYWRIGHT_RESET_DB
├── utils/
│   ├── regressionTest.ts    # fixture: login FRESH per-test (default untuk regresi)
│   ├── workerTest.ts        # fixture per-worker (DEPRECATED — hanya akan-dihapus/)
│   └── laravel.ts           # akses DB/artisan dari runner (mysql2 + execSync) — TANPA endpoint HTTP
├── storage/
│   ├── database/schema.sql  # struktur DB tes
│   ├── database/seed.sql    # data deterministik (INSERT IGNORE, ID rentang tinggi)
│   ├── database/desa.zip    # folder desa kanonik (berisi app_key & config)
│   └── fixtures/            # berkas unggah uji (xlsx, pdf, svg, dll.)
└── e2e/
    ├── smoke/               # ~50 tes, gate wajib, ~1 menit
    └── regression/          # 44+ folder domain (penduduk, surat, peta, ...)
        └── akan-dihapus/    # DI-IGNORE (testIgnore) — pakai workerTest lama, jangan disentuh
```

**Aturan penempatan:** tes baru diorganisasi **per domain fitur** (`regression/<domain>/`), bukan per nomor isu. Nama file deskriptif kebab-case (mis. `hapus-data-dengan-sub-garis.spec.ts`).

---

## 3. Model Sesi & Fixture

| Fixture | Kapan dipakai | Perilaku |
|---|---|---|
| `regressionTest` | **Semua tes regresi** | Login fresh per-test → sesi CI3 independen; aman dari kontensi paralel & sesi stale |
| `workerTest` | Hanya `akan-dihapus/` (legacy) | Satu sesi per worker → rawan stale saat paralel. **Jangan dipakai di tes baru.** |
| Tanpa fixture login | Tes alur **tak terautentikasi** | mis. `auth/pesan-error-validasi-login`, `security/autentikasi-bypass-*` |

```typescript
// Default untuk tes regresi:
import { test, expect } from '../../../utils/regressionTest';
```

---

## 4. Data Uji (`seed.sql`)

- Semua data yang dibutuhkan tes **harus ada di `seed.sql`** — **jangan hardcode** ID/nama dari database lokal.
- Gunakan `INSERT IGNORE` dan **ID jauh di atas range schema** (`tweb_penduduk` > 160000, `tweb_keluarga` > 50000) agar tidak bentrok.
- Untuk kolom yang sering hilang dari `schema.sql`, pakai pola idempoten `PREPARE/EXECUTE` (MySQL 8 tak mendukung `ADD COLUMN IF NOT EXISTS`).
- **`app_key`**: `seed.sql` menormalkan `config.app_key` agar **cocok dengan `desa/app_key`** di `desa.zip`. Bila `desa.zip` diganti, perbarui baris `UPDATE config SET app_key=...`.
- **Tes destruktif** (menghapus/mengubah data seed) harus **memulihkan dirinya sendiri** via `test.beforeEach` + `Laravel.query()` (`INSERT IGNORE`), bukan mengandalkan reset manual.

---

## 5. Pola Kritis (Anti-Flaky)

1. **Jangan `networkidle` di halaman admin** — polling background mencegahnya tercapai. Pakai `waitForLoadState('domcontentloaded')` atau `waitForSelector` elemen spesifik. (`networkidle` boleh di halaman publik/sebelum navigasi.)
2. **Timeout 15000ms** untuk semua assertion yang menunggu AJAX/DataTable/modal.
3. **Tunggu `table#tabeldata tbody tr`**, bukan hanya `table#tabeldata` (tabel ada di DOM sebelum AJAX selesai).
4. **Select2**: ketik NIK/ID di kotak cari sebelum memilih (pagination 25/halaman).
5. **Modal HAPUS**: tunggu `toBeFocused()` pada `#confirm-input` sebelum `fill('HAPUS')`.
6. **Navigasi URL langsung** alih-alih rantai klik yang bergantung urutan baris.
7. **Filter row berbasis teks** (`filter({ hasText })`), bukan indeks `nth()`.
8. **Selektor sesuai HTML aktual**: split-button Cetak/Unduh = `<a class="btn dropdown-toggle">`; tombol DataTable = `<button>`; header `<th>` ber-role `columnheader`.
9. **Hindari `try/catch` kosong** yang menelan assertion (menghasilkan hijau palsu). `catch {}` hanya untuk aksi opsional sejati (mis. menutup banner cookie).

---

## 6. Paralelisme & Server

- `playwright.config.ts`: `workers=4` lokal (`workers=1` di CI), `PHP_CLI_SERVER_WORKERS=12` (3× worker) agar pool PHP tidak antre.
- `dependencies: ['setup']` menjamin auth dibuat sebelum tes berjalan.
- Playwright memulai server PHP sendiri (`webServer`); jangan start manual.
- **Env yang di-set perintah `webServer`** (semua via env, bukan modifikasi source aplikasi) — penting agar `/siteman` sehat saat server start:

  | Env | Guna |
  |---|---|
  | `APP_ENV=testing` | Lingkungan Laravel "testing" → menetralkan gerbang `app()->environment(['testing'])` (langganan/token pelanggan, pengumuman) |
  | `CI_ENV=testing` | `ENVIRONMENT='testing'` di sisi CI3 (konsisten dengan PHPUnit/Larastan; **bukan** development) |
  | `APP_KEY=<kunci tes>` | `get_app_key()` memprioritaskan `env('APP_KEY')`; harus sama dengan `config.app_key` (`seed.sql`) & `desa/app_key` agar tak ter-redirect `koneksi_database` |
  | `DB_CONNECTION=default` | Koneksi default **bernama** `default` (config: `env('DB_CONNECTION','mysql')`); `app/Libraries/Database.php` memakai `getConnections()['default']` — tanpa ini bernama `mysql` → `null` → 500 |
  | `DEBUGBAR_ENABLED=false` | Matikan Debugbar lewat env (pengganti hack runtime di `bootstrap/app.php`) |
  | `OPENSID_DB_DATABASE` | Arahkan sisi CI3 ke DB tes tanpa mengubah `.env` |

- **Sandi admin tes = `Admin$123`** (memenuhi `syaratSandi`: huruf besar+kecil+angka+karakter khusus). Sandi lemah memicu `force_change_password` → login ter-redirect ke `pengguna#sandi`, bukan dashboard. Lihat `PLAYWRIGHT_AUTH_PASSWORD`.

---

## 7. Strategi CI/CD

| Lapis | Pemicu | Sifat | Berkas |
|---|---|---|---|
| **Tripwire collection** (`--list`) | Setiap PR | Menggagalkan PR bila ada syntax error / modul hilang sebelum tes dijalankan | `.github/workflows/playwright.yml` (job `collection`) |
| **Smoke** (`e2e/smoke`) | Setiap PR | **Wajib (blocking)** — cepat, membuktikan login/seed/server/konfigurasi sehat | `.github/workflows/playwright.yml` (job `smoke`) |
| **Regresi penuh** (`e2e/regression`) | Nightly (`schedule`) + manual (`workflow_dispatch`) | Informasional (`continue-on-error`), **tidak** memblokir PR; dipromosikan ke blocking per folder setelah stabil hijau | `.github/workflows/playwright-regression.yml` |

Setup berat bersama (PHP+Composer, Node+npm, Chromium, mysql-client, bypass IonCube) ada di composite action `.github/actions/setup-opensid-test`. DB tes CI: `opensid_playwright`. Memicu regresi manual: Actions → *Playwright Regression (Nightly)* → **Run workflow**.

**Provisioning DB di CI (urutan penting):** Playwright mengecek kesiapan `webServer.url` (`/siteman`) **sebelum** `globalSetup`. Karena itu DB harus sudah lengkap saat server start. Maka:

- Di **CI**, ada langkah workflow **"Provision test database"** (`schema.sql` + `seed.sql` + ekstrak `desa.zip` + `opensid:setup`) yang berjalan **sebelum** `npx playwright test`.
- `globalSetup` hanya memprovisi bila **`PLAYWRIGHT_RESET_DB=true`** (lokal) — di CI ia **tidak** memprovisi (menghindari ketergantungan urutan & provisi ganda).
- Env job CI: `APP_ENV=testing`, `CI_ENV=testing`, `APP_KEY`, `DB_CONNECTION=default`, `DEBUGBAR_ENABLED=false`, `DB_*`, `PLAYWRIGHT_AUTH_PASSWORD=Admin$123` (lihat §6).

---

## 8. Utang Teknis & Konsistensi yang Diketahui

Hasil audit saat migrasi (lihat dokumen progres migrasi untuk detail):

- **±94 spec masih memakai `networkidle`** dan **±47 memakai `waitForTimeout`** — sebagian melanggar Pola §5. Perlu dibersihkan bertahap per folder saat triase.
- **`workerTest`** masih dipakai 17 spec di `akan-dihapus/` (sengaja di-ignore).
- **Kompatibilitas DB tes** dengan rilis-dev sudah diselesaikan: kredensial DB digerakkan env, `desa.zip` diekstrak ke root. Smoke **50/50 hijau** (lokal & CI), PHPUnit **lulus**.
- **Stabilisasi pasca-merge `rilis-dev`** (semua via ENV/seed, **tanpa logika khusus-tes di source aplikasi** — sesuai review maintainer): Debugbar dimatikan via `DEBUGBAR_ENABLED=false` (blok `OPENSID_NO_DEBUGBAR` di `bootstrap/app.php` **dihapus**); `APP_KEY` tes disamakan dengan `config.app_key`/`desa.zip`; `APP_ENV=testing` + `CI_ENV=testing`; `DB_CONNECTION=default`; provisi DB di langkah workflow sebelum Playwright; sandi admin seed `Admin$123` (lolos `syaratSandi`). Lihat §6–§7.
- **`schema.sql` adalah fixture BERSAMA** Playwright + PHPUnit (`RefreshDatabase --schema-path`). Sumber kebenarannya adalah **schema rilis-dev** — jangan menimpanya dengan snapshot lain (pernah membuat PHPUnit crash karena 16 tabel hilang). Data spesifik tes → lewat `seed.sql`, bukan `schema.sql`.
- Saat menambah/memperbaiki tes, ikuti checklist Pola §5 dan jangan memperkenalkan kembali anti-pattern di atas.

---

## 9. Referensi

| Berkas | Isi |
|---|---|
| [panduan-menjalankan-tes-playwright-lokal.md](panduan-menjalankan-tes-playwright-lokal.md) | Setup & cara menjalankan |
| `migrasi-playwright-progress.md` | Status & langkah migrasi dari er-laravel |
| `playwright.config.ts` | Konfigurasi runner |
| `tests/playwright/utils/` | Fixture & helper DB |
