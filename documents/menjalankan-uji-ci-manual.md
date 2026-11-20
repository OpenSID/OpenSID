# Menjalankan Uji CI Secara Manual (Saat Kuota GitHub Actions Habis)

## Latar belakang

OpenDesa memakai kuota GitHub Actions dari paket **gratis untuk organisasi nonprofit**, sehingga
kuota sering habis dan gerbang CI pada Pull Request tidak dapat berjalan. Dokumen ini memandu
**reviewer** menjalankan gerbang-gerbang CI itu **secara manual di komputer sendiri**, agar sebuah
PR tetap dapat diverifikasi (lolos/gagal) tanpa menunggu Actions.

Prosedur ini **dapat diadaptasi** ke lingkungan reviewer — umumnya **Windows** atau **Linux**.
Perbedaan antar sistem operasi dijelaskan di tiap langkah.

## Gerbang CI yang direproduksi

Sumber: `.github/workflows/`.

| # | Gerbang | Workflow | Butuh DB? | Perkiraan waktu |
|---|---|---|---|---|
| A | **Larastan (PHPStan)** | `larastan.yml` | tidak* | ~1–2 mnt |
| B | **Collection tripwire** | `playwright.yml` (job `collection`) | tidak | < 1 mnt |
| C | **Unit + Feature Test** | `test.yml` | ya | ~3–5 mnt |
| D | **Smoke gate** | `playwright.yml` (job `smoke`) | ya | ~3–5 mnt + setup |

> \*Analisis PHPStan tidak menjalankan aplikasi, jadi tidak butuh DB. CI hanya memakai DB untuk
> `ide-helper:models` (lihat Gerbang A). Untuk verifikasi PR, menjalankan PHPStan langsung sudah cukup.

**Saran urutan:** A → B dulu (cepat, bernilai tinggi, tanpa risiko), lalu C, lalu D (paling berat).

---

## Prasyarat (sekali per komputer)

- **PHP 8.4** dengan ekstensi:
  `mbstring, xml, mysqli, pdo, pdo_mysql, fileinfo, intl, bcmath, tokenizer, ctype, json, openssl, gd, tidy, zip, dom, curl, libxml, pcntl, sqlite, pdo_sqlite, soap, exif, iconv`.
  (Ekstensi `ioncube` dipakai di server produksi; untuk lokal **tidak wajib** — lihat "Bypass IonCube".)
- **Composer 2.x**
- **Node.js ≥ 20** + npm
- **Server MySQL 8 / MariaDB** yang berjalan
- **Klien baris-perintah `mysql`** tersedia di `PATH` (dipakai uji berbasis DB & Playwright)
- **Browser Playwright (Chromium)** — untuk gerbang B & D

**Windows:** cara termudah memenuhi PHP 8.4 + MySQL + klien `mysql` adalah **Laragon** atau **XAMPP**
(pastikan folder `php` dan `mysql/bin` masuk `PATH`). Node dari nodejs.org. Jalankan perintah di
**PowerShell** atau **Git Bash**.

**Linux (Debian/Ubuntu):**
```bash
sudo apt-get update
sudo apt-get install -y php8.4-cli php8.4-{mysql,mbstring,xml,intl,bcmath,gd,zip,curl,soap,sqlite3,tidy} \
     mysql-client nodejs npm
```

Instal browser Playwright (sekali):
```bash
npx playwright install --with-deps chromium   # Linux
npx playwright install chromium               # Windows/macOS
```

---

## Menyetel variabel lingkungan (rujukan lintas-OS)

Beberapa gerbang butuh variabel lingkungan. Cara menyetelnya berbeda per shell:

| Shell | Menyetel satu variabel | Inline (sebelum perintah) |
|---|---|---|
| **Bash** (Linux/macOS/Git Bash) | `export FOO=bar` | `FOO=bar BAR=baz perintah` |
| **PowerShell** (Windows) | `$env:FOO = "bar"` | `$env:FOO="bar"; $env:BAR="baz"; perintah` |
| **CMD** (Windows) | `set FOO=bar` | `set FOO=bar && perintah` |

> Ganti kredensial DB (`DB_USERNAME`, `DB_PASSWORD`, `DB_HOST`) pada seluruh contoh dengan
> kredensial MySQL Anda. Contoh memakai user `root` tanpa password; sesuaikan seperlunya.

---

## Langkah 0 — Ambil branch PR

Dengan GitHub CLI (paling mudah):
```bash
gh pr checkout <NOMOR_PR>
```
Atau manual:
```bash
git fetch origin
git checkout <nama-branch-pr>
```

> **Disarankan bekerja di salinan kerja terpisah** (clone khusus review) agar provisioning Gerbang C/D
> tidak mengubah instalasi dev Anda. Lihat catatan di Gerbang D.

## Langkah 1 — Pasang dependensi & bypass IonCube

```bash
composer install --no-progress --prefer-dist --no-interaction
npm ci
```

**Bypass IonCube** (agar `php -S` bisa boot Premium tanpa loader IonCube). `index.php` mengizinkan
boot bila ada `phpunit.xml` **atau** `.env.testing`:
```bash
# Bash
cp phpunit.xml.dist phpunit.xml
```
```powershell
# PowerShell
Copy-Item phpunit.xml.dist phpunit.xml
```

Pastikan `.env` ada (salin dari `.env.example` bila perlu) dan berisi kredensial MySQL Anda
(`DB_HOST`, `DB_USERNAME`, `DB_PASSWORD`).

---

## Gerbang A — Larastan (PHPStan)

```bash
# Bash
cp phpstan.neon.dist phpstan.neon
vendor/bin/phpstan analyse --memory-limit=2G --no-progress
```
```powershell
# PowerShell
Copy-Item phpstan.neon.dist phpstan.neon
vendor/bin/phpstan analyse --memory-limit=2G --no-progress
```

**Lolos** bila keluar `[OK] No errors`.

Untuk kesetaraan penuh dengan CI (opsional; butuh DB terkonfigurasi), CI lebih dulu menjalankan
`php artisan ide-helper:models --write` untuk memperbarui docblock `@property` model. Biasanya tidak
diperlukan karena model yang di-commit sudah mutakhir.

> Catatan: `phpstan.neon` di-gitignore; itu hanya salinan lokal dari `.dist`.

## Gerbang B — Collection tripwire (Playwright)

Meng-*compile* seluruh spec Playwright (menangkap syntax/import error). Tidak butuh DB/aplikasi.
```bash
npx playwright test --list
```
**Lolos** bila berakhir `Total: N tests in M files` tanpa error.

## Gerbang C — Unit + Feature Test (PHPUnit)

Butuh database uji. Uji Feature memakai `RefreshDatabase` yang memuat
`tests/playwright/storage/database/schema.sql` melalui klien `mysql` (**wajib di PATH**).

1. **Buat database uji** (nama default dari `phpunit.xml.dist`: `opensid-laravel-test`):
   ```bash
   mysql -h 127.0.0.1 -u root -e "CREATE DATABASE IF NOT EXISTS \`opensid-laravel-test\`;"
   ```
2. **Siapkan aplikasi** (menulis `desa/config`):
   ```bash
   php artisan opensid:setup
   ```
3. **Jalankan uji:**
   ```bash
   # Cepat (subset Unit):
   vendor/bin/phpunit --testsuite Unit

   # Setara CI (Unit + Feature):
   vendor/bin/phpunit
   ```

**Lolos** bila `0 failed` (baris skipped wajar).

> Bila muncul `sh: mysql: command not found` / `'mysql' is not recognized`, klien `mysql` belum di
> `PATH` — tambahkan folder `bin` MySQL Anda ke `PATH`.

## Gerbang D — Smoke gate (Playwright terhadap aplikasi nyata)

Smoke menjalankan **aplikasi sungguhan** (`php -S` + DB `opensid_playwright`). Provisioning-nya
meng-`unzip desa.zip` yang **menimpa folder `desa/`** dan menulis ulang `desa/config`.

> ⚠️ **Jalankan di salinan kerja terpisah** (clone khusus, atau `git worktree`) agar folder `desa/`
> instalasi dev Anda tidak tertimpa. Alternatif tanpa unzip: lihat "Cara non-destruktif" di bawah.

### D.1 Provisioning database uji

```bash
mysql -h 127.0.0.1 -u root -e "CREATE DATABASE IF NOT EXISTS opensid_playwright;"
mysql -h 127.0.0.1 -u root opensid_playwright < tests/playwright/storage/database/schema.sql
mysql -h 127.0.0.1 -u root opensid_playwright < tests/playwright/storage/database/seed.sql
```

### D.2 Sediakan folder desa fixture + setup

```bash
# Bash
unzip -o tests/playwright/storage/database/desa.zip -d .
DB_DATABASE=opensid_playwright OPENSID_DB_DATABASE=opensid_playwright APP_ENV=testing php artisan opensid:setup
```
```powershell
# PowerShell (Expand-Archive tidak menimpa; hapus desa lama dulu bila perlu)
Expand-Archive -Force tests/playwright/storage/database/desa.zip -DestinationPath .
$env:DB_DATABASE="opensid_playwright"; $env:OPENSID_DB_DATABASE="opensid_playwright"; $env:APP_ENV="testing"
php artisan opensid:setup
```

### D.3 Jalankan smoke

Server web (`php -S`) dijalankan otomatis oleh Playwright (perintahnya sudah menyesuaikan
Windows/Unix di `playwright.config.ts`). **`DB_DATABASE` harus di-set sebagai env NYATA** agar
`desa/config` me-resolve DB uji (jika tidak: error `Incorrect database name ''`). **Jangan** set
`PLAYWRIGHT_RESET_DB`.

```bash
# Bash
DB_CONNECTION=default DB_HOST=127.0.0.1 DB_DATABASE=opensid_playwright DB_USERNAME=root DB_PASSWORD= \
PLAYWRIGHT_BASE_URL="http://127.0.0.1:8000" \
PLAYWRIGHT_DB_HOST=127.0.0.1 PLAYWRIGHT_DB_DATABASE=opensid_playwright \
PLAYWRIGHT_DB_USERNAME=root PLAYWRIGHT_DB_PASSWORD= \
PLAYWRIGHT_AUTH_USERNAME=admin PLAYWRIGHT_AUTH_PASSWORD='Admin$123' \
  npx playwright test e2e/smoke --reporter=line
```
```powershell
# PowerShell
$env:DB_CONNECTION="default"; $env:DB_HOST="127.0.0.1"; $env:DB_DATABASE="opensid_playwright"
$env:DB_USERNAME="root"; $env:DB_PASSWORD=""
$env:PLAYWRIGHT_BASE_URL="http://127.0.0.1:8000"
$env:PLAYWRIGHT_DB_HOST="127.0.0.1"; $env:PLAYWRIGHT_DB_DATABASE="opensid_playwright"
$env:PLAYWRIGHT_DB_USERNAME="root"; $env:PLAYWRIGHT_DB_PASSWORD=""
$env:PLAYWRIGHT_AUTH_USERNAME="admin"; $env:PLAYWRIGHT_AUTH_PASSWORD='Admin$123'
npx playwright test e2e/smoke --reporter=line
```

**Lolos** bila berakhir `N passed`. Laporan HTML: `tests/playwright/report/`.

### Cara non-destruktif (tanpa unzip desa.zip)

Bila ingin memakai instalasi yang ada tanpa menimpa `desa/`: **lewati langkah unzip**, dan pastikan
`DB_DATABASE=opensid_playwright` di-set sebagai env nyata (sudah ada pada perintah D.3) sehingga
`desa/config` Anda me-resolve DB uji. Lebih cepat, walau sebagian assertion berbasis konten fixture
bisa berbeda dari CI.

### Salinan kerja terpisah (opsional, paling bersih)

```bash
git worktree add --detach ../review-smoke <nama-branch-pr>
cd ../review-smoke
# ulangi Langkah 1 (composer install, cp phpunit.xml.dist phpunit.xml) lalu Gerbang D
# selesai:
cd -   && git worktree remove --force ../review-smoke
```
Atau cukup `git clone` branch ke folder lain. (Catatan: `vendor/` harus di-`composer install` nyata —
symlink merusak path autoload Composer.)

---

## Melaporkan hasil di PR

Setelah menjalankan gerbang yang relevan, tuliskan hasil sebagai komentar PR, misalnya:

```
Uji CI dijalankan manual (kuota Actions habis):
- Larastan        : [OK] No errors
- Collection      : 650 tests ter-compile
- Unit + Feature  : 891 passed, 5 skipped, 0 failed
- Smoke gate      : 50 passed
Lingkungan: Windows 11 / PHP 8.4 / MySQL 8.
```

## Ringkasan checklist reviewer

- [ ] `gh pr checkout <NOMOR_PR>`
- [ ] `composer install` + `npm ci` + `cp phpunit.xml.dist phpunit.xml`
- [ ] **A** `vendor/bin/phpstan analyse --memory-limit=2G --no-progress` → `[OK] No errors`
- [ ] **B** `npx playwright test --list` → `Total: N tests`
- [ ] **C** buat DB uji + `php artisan opensid:setup` + `vendor/bin/phpunit` → 0 gagal
- [ ] **D** provisioning `opensid_playwright` + `npx playwright test e2e/smoke` → `N passed`
- [ ] Laporkan hasil di komentar PR

---

## Pemecahan masalah

| Gejala | Penyebab & solusi |
|---|---|
| Halaman "IonCube Loader Diperlukan" | `phpunit.xml` tidak ada. Jalankan `cp phpunit.xml.dist phpunit.xml`. |
| `mysql: command not found` / `is not recognized` | Klien `mysql` belum di `PATH`. Tambahkan folder `bin` MySQL. |
| Smoke 500 `Incorrect database name ''` | `DB_DATABASE` tidak di-set sebagai env nyata. Set `DB_DATABASE=opensid_playwright`. |
| `Timed out waiting from config.webServer` | DB belum diprovisi sebelum run, atau app gagal boot. Selesaikan D.1–D.2 lebih dulu; jangan set `PLAYWRIGHT_RESET_DB`. |
| Port 8000 sudah dipakai | Hentikan proses lama. Linux/macOS: `lsof -ti :8000 \| xargs kill -9`. Windows: `netstat -ano \| findstr :8000` lalu `taskkill /PID <pid> /F`. |
| PHPStan merah setelah merge `rilis-dev` | Baseline bisa ter-reset saat merge. Jalankan ulang Gerbang A; perbaiki akar masalah (jangan tambah baseline). |
| Larastan `ignore.unmatched` | Ada entri baseline usang untuk kode yang sudah berubah — hapus entri itu dari `phpstan-baseline.neon`. |
