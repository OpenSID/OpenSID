# Panduan Menjalankan Tes Playwright Secara Lokal

Dokumen ini menjelaskan cara menyiapkan lingkungan dan menjalankan suite pengujian Playwright OpenSID di mesin lokal. Untuk **strategi & pola penulisan tes**, lihat [strategi-pengujian-playwright.md](strategi-pengujian-playwright.md).

> Suite ini hasil migrasi dari branch `er-laravel`. Struktur: `tests/playwright/e2e/{smoke,regression}/`. Lihat `migrasi-playwright-progress.md` untuk status migrasi.

---

## 1. Prasyarat

| Komponen | Versi diuji | Catatan |
|---|---|---|
| PHP | 8.4 | Ekstensi: `pdo_mysql`, `mysqli`, `gd`, `mbstring`, `zip`, dll. |
| Node.js | ≥ 20 (diuji 24) | |
| MySQL | 8.0 | Bisa via DBngin, Homebrew, atau Docker |
| Klien `mysql` | 8.x | Untuk impor schema/seed (mis. `mysql-client` Homebrew) |
| Browser | Chromium | Dipasang oleh Playwright |

### Catatan IonCube (build Premium)
File premium digerbang oleh pemeriksaan IonCube di `index.php`. Gerbang ini **otomatis dilewati** jika ada file `phpunit.xml` di root. Untuk pengujian lokal, cukup buat sekali:
```bash
cp phpunit.xml.dist phpunit.xml   # phpunit.xml di-gitignore; aman untuk lokal
```
`php artisan ...` (CLI) tidak melewati gerbang ini; hanya server web (`php -S`) yang memerlukannya.

---

## 2. Instalasi Dependensi

```bash
npm install                       # termasuk @playwright/test, mysql2, xlsx
npx playwright install chromium   # unduh browser
```

---

## 3. Menyiapkan Database Tes

Suite memakai **database tes terpisah** bernama `opensid_playwright` (tidak menyentuh DB dev).

```bash
# Sesuaikan path biner mysql Anda
MYSQL="mysql"   # atau mis. /usr/local/opt/mysql-client/bin/mysql

# 1. Buat database
"$MYSQL" -h 127.0.0.1 -u root -e \
  "CREATE DATABASE IF NOT EXISTS opensid_playwright CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;"
```

Provisioning data dilakukan otomatis oleh `globalSetup` saat `PLAYWRIGHT_RESET_DB=true` (lihat §5) **atau** manual:
```bash
"$MYSQL" -h 127.0.0.1 -u root opensid_playwright < tests/playwright/storage/database/schema.sql
"$MYSQL" -h 127.0.0.1 -u root opensid_playwright < tests/playwright/storage/database/seed.sql
unzip -o tests/playwright/storage/database/desa.zip -d .   # PENTING: ke ROOT, bukan -d desa
APP_ENV=testing CI_ENV=testing APP_KEY='base64:/2PUGKKo+G4EqZ84quM4sVXS2oogaxOKjW136Rl1pHY=' \
  DB_CONNECTION=default OPENSID_DB_DATABASE=opensid_playwright \
  DB_HOST=127.0.0.1 DB_USERNAME=root DB_PASSWORD= DB_DATABASE=opensid_playwright \
  php artisan opensid:setup
```

> ⚠️ **Ekstraksi desa.zip:** arsip sudah mengandung prefiks `desa/`, jadi ekstrak ke **root repo** (`-d .`) agar menghasilkan `desa/app_key`, bukan `desa/desa/app_key`.

> ℹ️ Saat tes berjalan, **perintah `webServer` di `playwright.config.ts` otomatis menyetel** `APP_ENV=testing`, `CI_ENV=testing`, `APP_KEY` (= `config.app_key`/`desa/app_key`), `DB_CONNECTION=default`, `DEBUGBAR_ENABLED=false`, `OPENSID_DB_DATABASE`. Jadi `.e2e.env` (§4) cukup memuat kredensial DB + kredensial login. (CI menyetel hal yang sama di `env:` job + langkah "Provision test database".)

---

## 4. Konfigurasi Environment (`.e2e.env`)

Buat `.e2e.env` di root (di-gitignore). Contoh untuk MySQL lokal (root tanpa password):

```dotenv
PLAYWRIGHT_BASE_URL=http://127.0.0.1:8000
PLAYWRIGHT_AUTH_USERNAME=admin
# seed.sql menyetel sandi admin ke "Admin$123" (memenuhi syaratSandi: huruf
# besar+kecil+angka+karakter khusus); default "sid304"/sandi lemah memicu
# redirect paksa-ganti-sandi sehingga login tak sampai ke dashboard.
PLAYWRIGHT_AUTH_PASSWORD=Admin$123

# Dipakai util Playwright (laravel.ts, globalSetup) untuk koneksi mysql2 langsung
PLAYWRIGHT_DB_HOST=127.0.0.1
PLAYWRIGHT_DB_PORT=3306
PLAYWRIGHT_DB_USERNAME=root
PLAYWRIGHT_DB_PASSWORD=
PLAYWRIGHT_DB_DATABASE=opensid_playwright

# WAJIB: diturunkan ke server PHP. Sisi CI3 (desa/config/database.php) membaca
# getenv('DB_*'), jadi tanpa ini server akan redirect ke /koneksi_database.
DB_HOST=127.0.0.1
DB_PORT=3306
DB_USERNAME=root
DB_PASSWORD=
DB_DATABASE=opensid_playwright
```

---

## 5. Menjalankan Tes

> **Jangan** menjalankan `php -S` secara manual sebelum tes. Playwright memulai servernya sendiri (lihat `webServer` di `playwright.config.ts`). Jika port 8000 tersangkut proses lama:
> ```bash
> kill $(lsof -ti :8000) 2>/dev/null; sleep 2
> ```

### Reset + provisioning DB otomatis
Set `PLAYWRIGHT_RESET_DB=true` agar `globalSetup` mengimpor `schema.sql` + `seed.sql`, ekstrak `desa.zip`, jalankan `opensid:setup`, dan bersihkan cache:
```bash
# PLAYWRIGHT_MYSQL_BIN bila biner mysql tidak ada di PATH
PLAYWRIGHT_RESET_DB=true PLAYWRIGHT_MYSQL_BIN="$MYSQL" npx playwright test
```
Tanpa `PLAYWRIGHT_RESET_DB`, suite memakai data DB yang ada (lebih cepat).

### Smoke gate (cepat, ~1 menit)
```bash
npx playwright test e2e/smoke --workers=4
```

### Satu folder regresi
```bash
npx playwright test e2e/regression/penduduk/ --workers=1   # diagnosa bersih
npx playwright test e2e/regression/penduduk/ --workers=4   # verifikasi paralel
```

### Satu file / satu tes
```bash
npx playwright test e2e/regression/surat/buat-surat.spec.ts --workers=1
npx playwright test -g "judul tes" --workers=1
```

### Laporan
```bash
npx playwright show-report tests/playwright/report   # HTML
# Hasil JSON: tests/playwright/report/test-results.json
```

---

## 6. Pemecahan Masalah Umum

| Gejala | Penyebab & Solusi |
|---|---|
| `Timed out waiting 60000ms from config.webServer` | Port 8000 tersangkut → `kill $(lsof -ti :8000)`. Atau server balas 500/302 saat start (lihat baris di bawah). |
| Redirect `/koneksi_database` saat login | `DB_*` tidak ter-set di env server (lihat §4), **atau** `desa/app_key` ≠ `config.app_key`. Samakan: `UPDATE config SET app_key='<isi desa/app_key>' WHERE id=1;` lalu hapus cache. |
| HTTP 500 `The MAC is invalid` saat boot | `desa.zip` lama membawa password DB terenkripsi yang tak cocok app_key rilis-dev. Gunakan `desa.zip` terbaru (berbasis `getenv`) dari branch ini, lalu provisioning ulang. |
| Halaman "IonCube Loader Tidak Ditemukan" | Buat `phpunit.xml` (§1). |
| DataTable kosong / `tbody tr` timeout | Data seed kurang. Tambah ke `seed.sql` (lihat strategi), lalu reset DB. |
| Cache basi setelah ubah `seed.sql` manual | `rm -rf storage/framework/cache/data/*` (otomatis bila `PLAYWRIGHT_RESET_DB=true`). |

---

## 7. Ringkas (TL;DR)

```bash
cp phpunit.xml.dist phpunit.xml
npm install && npx playwright install chromium
mysql -h127.0.0.1 -uroot -e "CREATE DATABASE IF NOT EXISTS opensid_playwright"
# buat .e2e.env (lihat §4)
kill $(lsof -ti :8000) 2>/dev/null
PLAYWRIGHT_RESET_DB=true PLAYWRIGHT_MYSQL_BIN=mysql npx playwright test e2e/smoke --workers=4
```
