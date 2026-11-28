# Panduan Struktur Folder `public`

> **Mulai dari mana?**
>
> | Anda siapa | Baca bagian |
> |---|---|
> | Pengelola situs desa yang **memperbarui aplikasi** | [Bagian A](#bagian-a--untuk-pengelola-situs-desa) |
> | Pengembang atau reviewer yang **menjalankan branch di lokal** | [Bagian B](#bagian-b--untuk-pengembang--reviewer) |
> | Keduanya — cara mengarahkan document root & pemecahan masalah | [Bagian C](#bagian-c--referensi-bersama) |

---

## Apa yang berubah

Dulu semua berkas aplikasi berada dalam satu folder yang dibuka untuk umum —
termasuk `.env` yang berisi kata sandi database. Yang menjaganya cuma berkas
`.htaccess`, dan berkas itu bisa hilang atau tidak dibaca server.

Sekarang hanya folder `public/` yang dibuka untuk umum. Sisanya secara fisik
berada di luar jangkauan pengunjung internet — bukan sekadar "dilarang", tetapi
memang **tidak bisa ditemukan**.

Analoginya: dulu pintu depan kantor desa langsung membuka ke seluruh gedung,
dan yang menahan tamu masuk ke ruang arsip cuma selembar kertas bertuliskan
"dilarang masuk". Sekarang tamu hanya bisa masuk ke ruang tamu. Ruang arsip
berada di lantai lain yang tidak ada pintunya dari luar.

| Sebelum | Sesudah |
|---|---|
| `index.php` di root proyek | `public/index.php` |
| `assets/` | `public/assets/` |
| `rfm/` | `public/rfm/` |
| `favicon.ico` | `public/favicon.ico` |
| Document root = root proyek | Document root = `public/` |
| `.htaccess` disalin manual | `public/.htaccess` ikut Git |
| `desa/` | **tidak pindah** — dijembatani symlink |
| `storage/`, `Modules/`, `vendor/` | **tidak pindah** |

---

# Bagian A — Untuk pengelola situs desa

> Panduan ini **wajib dibaca sebelum memperbarui**, karena situs desa Anda akan
> mati sementara jika langkah di sini dilewati.

## Yang HARUS Anda lakukan

Satu hal saja: **arahkan document root web server ke folder `public`.**

Caranya berbeda tergantung hosting. Cari bagian yang sesuai di bawah ini.

## A1. VPS atau server sendiri

Paling mudah. Lihat [Bagian C1 — Mengarahkan document root](#c1-mengarahkan-document-root)
untuk konfigurasi Apache, nginx, dan OpenLiteSpeed.

## A2. cPanel — subdomain atau addon domain

Ini pilihan **terbaik** jika Anda memakai cPanel.

1. cPanel → **Domains** → **Create A New Domain**
2. Isi nama domain/subdomain
3. **Hilangkan centang** "Share document root"
4. Pada **Document Root**, isi: `opensid/public`
   (sesuaikan `opensid` dengan nama folder aplikasi Anda)
5. Klik **Submit**

`public/.htaccess` sudah ikut dalam repo, jadi tidak ada langkah tambahan.

## A3. cPanel — domain utama

Domain utama cPanel dikunci ke `public_html/`. Ada dua cara.

### A3a. Susun ulang folder — AMAN, disarankan

Hasil akhir yang dituju:

```
/home/user/
├── opensid/              ← seluruh aplikasi, DI LUAR jangkauan internet
│   ├── .env
│   ├── vendor/
│   ├── app/
│   ├── desa/
│   └── storage/
└── public_html/          ← isi folder public/ dipindah ke sini
    ├── index.php
    ├── assets/
    ├── rfm/
    ├── desa/  (symlink)
    └── storage/ (symlink)
```

Langkahnya, lewat Terminal cPanel atau SSH:

```bash
cd /home/user

# 1. Cadangkan dulu — JANGAN dilewati
tar -czf backup-sebelum-migrasi.tar.gz public_html

# 2. Pindahkan aplikasi keluar dari public_html
mkdir -p opensid
mv public_html/* public_html/.[!.]* opensid/ 2>/dev/null

# 3. Pindahkan isi public ke public_html
mv opensid/public/* opensid/public/.[!.]* public_html/ 2>/dev/null
rmdir opensid/public
```

Isi `public_html/index.php` yang benar setelah penyusunan ulang:

```php
require __DIR__.'/../opensid/vendor/autoload.php';
$app = require_once __DIR__.'/../opensid/bootstrap/app.php';
```

**Jangan lupa file manager (`rfm`).** Dua berkas ini menghitung lokasi root
aplikasi dari posisi berkasnya sendiri, jadi ikut bergeser pada susunan ini:

`public_html/rfm/init.php`:
```php
// bawaan: dirname(__FILE__, 3)
define('RFM_BASE_PATH', dirname(__FILE__, 2) . '/opensid');
```

`public_html/rfm/config/config.php` — dua baris path unggahan:
```php
// bawaan: dirname(__DIR__, 3) . '/desa/upload/media/'
'current_path'      => dirname(__DIR__, 3) . '/opensid/desa/upload/media/',
'thumbs_base_path'  => dirname(__DIR__, 3) . '/opensid/desa/upload/thumbs/',
```

Tanpa penyesuaian ini, tombol pilih gambar pada artikel dan template surat
akan gagal membuka file manager.

Lalu buat ulang symlink:
```bash
cd /home/user/public_html
mkdir -p desa
ln -s ../../opensid/desa/upload     desa/upload
ln -s ../../opensid/desa/logo       desa/logo
ln -s ../../opensid/desa/pengaturan desa/pengaturan
ln -s ../../opensid/desa/anjungan   desa/anjungan
ln -s ../opensid/storage/app/public storage
```

> `desa/themes` **tidak** disymlink. Folder tema berisi kode sumber
> (`.blade.php`, `config.json`) yang tidak boleh terbaca dari internet. Aset
> tema disajikan lewat route `theme_asset`, bukan sebagai berkas statis.

### A3b. Forwarder — CEPAT, tapi TIDAK menambah keamanan

Kalau Anda benar-benar tidak bisa melakukan A3a:

```bash
cd /home/user/public_html
cp htaccess-forwarder.apache.txt .htaccess
```

> ⚠️ **Ini kompromi, bukan solusi.** Situs akan jalan, tetapi `.env` dan
> `desa/config/database.php` **tetap dapat diakses dari internet**. Anda tidak
> mendapat manfaat keamanan apa pun.
>
> Halaman **Info Sistem** akan menampilkan peringatan merah selama Anda masih
> memakai cara ini. Peringatan itu benar — jangan diabaikan.

## A4. Instalasi di subfolder (`domain.com/opensid/`)

Document root tidak bisa diubah per subfolder, jadi hanya forwarder yang
memungkinkan. Ikuti langkah **A3b**, lalu aktifkan `RewriteBase` pada
`public/.htaccess`:

```apache
RewriteBase /opensid/
```

Berlaku peringatan yang sama seperti A3b.

## Ringkasan pilihan hosting

| Hosting Anda | Cara | Aman penuh? | Sulitnya |
|---|---|---|---|
| VPS / server sendiri | A1 | ✅ Ya | Mudah |
| cPanel subdomain / addon domain | A2 | ✅ Ya | Mudah |
| cPanel domain utama | A3a | ✅ Ya | Sedang |
| cPanel domain utama (terpaksa) | A3b | ❌ Tidak | Mudah |
| Subfolder | A4 | ❌ Tidak | Mudah |

**Jika memungkinkan, hindari A3b dan A4.** Keduanya membuat situs berjalan
tanpa memberi manfaat keamanan sama sekali.

Setelah selesai, lanjutkan ke [Bagian C2 — Daftar periksa](#c2-daftar-periksa-setelah-selesai).

---

# Bagian B — Untuk pengembang & reviewer

## Ringkas — untuk yang sudah paham

```bash
git fetch origin && git checkout feat/pindah-index-ke-public
composer install
php artisan storage:link --relative
php artisan serve
```

Buka `http://127.0.0.1:8000`. Selesai.

## B1. Prasyarat

| Kebutuhan | Catatan |
|---|---|
| PHP 8.4 | Ekstensi seperti biasa |
| Composer | |
| MySQL / MariaDB | |
| Sistem yang mendukung symlink | Semua Linux/macOS. Windows perlu Developer Mode atau jalankan sebagai Administrator |

## B2. Menjalankan pertama kali

### Ambil branch

```bash
git fetch origin
git checkout feat/pindah-index-ke-public
composer install
```

> **Bila `vendor/` sudah ada dan tidak ingin memasang ulang**, cukup
> `composer dump-autoload`. Repo memakai `optimize-autoloader`, dan classmap
> lama masih mencatat `app/Console/Commands/ServeCommand.php` yang sudah
> dihapus.

> **Catatan untuk klon baru.** `composer install` menjalankan `copy-file` yang
> menyalin TinyMCE dari `vendor/tinymce/tinymce` ke `public/assets/js/tinymce-72`,
> beserta plugin `image` kustom. Bila setelahnya muncul folder `assets/` di
> **root proyek**, berarti `composer.json` masih memakai tujuan lama — periksa
> bagian `extra.copy-file`. Tujuannya harus `public/assets/...`.
>
> Baris `"storage/app/vendor": "vendor"` **tidak** ikut berubah. Itu bukan aset
> web, melainkan tambalan berkas `vendor/` — font tambahan TCPDF (termasuk
> aksara Bali) dan perbaikan `spipu/html2pdf`. Karena `vendor/` dihapus dan
> diunduh ulang setiap `composer install`, tambalan itu disimpan di
> `storage/app/vendor` yang dilacak Git, lalu dipasang ulang otomatis.

### Buat symlink

```bash
php artisan storage:link --relative
```

Menghasilkan lima symlink:

```
public/desa/upload      -> ../../desa/upload/
public/desa/logo        -> ../../desa/logo/
public/desa/pengaturan  -> ../../desa/pengaturan/
public/desa/anjungan    -> ../../desa/anjungan/
public/storage          -> ../storage/app/public/
```

> Jika `desa/` belum ada (belum pernah dipasang), sebagian link belum dapat
> dibuat. Wizard instalasi akan membuatnya sendiri pada langkah migrasi.

### Jalankan

```bash
php artisan serve
```

Buka `http://127.0.0.1:8000`.

## B3. Memasang dari nol

Untuk menguji wizard instalasi dari awal:

```bash
cd <folder proyek>

# 1. Hapus penanda "sudah terinstall" beserta symlink-nya
rm -rf desa public/desa

# 2. Bersihkan sesi & cache instalasi sebelumnya
rm -rf storage/framework/sessions/* storage/framework/cache/data/*

# 3. Hapus database lama, buat database kosong baru lewat phpMyAdmin
```

Lalu buka `http://127.0.0.1:8000/install` dan ikuti 10 langkah wizard.

**Yang JANGAN dihapus:**

| | Alasan |
|---|---|
| `public/storage` | Menunjuk `storage/app/public` yang tidak ikut dihapus — tetap sah |
| `public/assets`, `public/rfm` | Bagian aplikasi, bukan data |
| `.env` | Berisi `APP_KEY`; tanpa itu sesi & cookie lama tidak dapat didekripsi |
| `storage/app/themes` | Tema sistem bawaan aplikasi |

**Yang terjadi otomatis saat wizard berjalan:**

- Langkah 3 memeriksa izin tulis `storage/` dan `backup_inkremental/`
- Langkah 5 (migrasi) menjalankan `folder_desa()` lalu memasang symlink
- Status symlink tidak diperiksa di wizard — ditampilkan di **Info Sistem**
  setelah instalasi selesai, ketika hasilnya bermakna

## B4. Memperbarui instalasi yang sudah ada

```bash
git pull
```

**`git pull` saja sudah cukup — situs tetap jalan di URL yang sama.** Root
proyek membawa `index.php` dan `.htaccess` forwarder yang ikut ter-`pull`,
sehingga request diteruskan ke `public/` walau document root belum dipindahkan.

- **`composer install` tidak wajib untuk update ini.** PR pemindahan `index.php`
  tidak menyentuh `composer.lock`; request web tetap jalan di `vendor/` yang
  ada. `composer install` hanya diperlukan bila rilis *lain* mengubah
  dependency — itu bagian normal setiap rilis yang menyentuh
  `composer.json`/`composer.lock`, di luar cakupan pemindahan ini.
- **`php artisan migrate` tidak berefek untuk instalasi ini.** Berkas
  `app/database/migrations/Migrasi_*.php` tidak berformat konvensi Laravel;
  runner sebenarnya `App\Libraries\Database::checkMigration()` yang dipanggil
  otomatis pada setiap request halaman admin. Symlink folder publik dipasang
  otomatis oleh `Migrasi_required` bila ada yang belum ada — tidak perlu
  `storage:link` manual.

Setelah itu, arahkan document root demi keamanan — lihat
[Bagian C1](#c1-mengarahkan-document-root). Selama belum dipindahkan, halaman
Info Sistem menampilkan peringatan merah dan `.env` masih terbaca dari internet.

## B5. Menjalankan pengujian otomatis

```bash
./vendor/bin/phpunit
```

> Jangan menjalankan dua suite PHPUnit bersamaan — keduanya memakai database
> uji yang sama dan akan saling menghapus tabel.

---

# Bagian C — Referensi bersama

## C1. Mengarahkan document root

Ini satu-satunya langkah yang benar-benar wajib. Tanpa ini aplikasi tetap
berjalan, tetapi `.env` masih terbaca dari internet dan halaman Info Sistem
akan menampilkan peringatan merah.

### Apache

```apache
<VirtualHost *:80>
    ServerName desa-anda.id
    DocumentRoot /var/www/opensid/public

    <Directory /var/www/opensid/public>
        AllowOverride All
        Require all granted
        Options -Indexes +FollowSymLinks
    </Directory>
</VirtualHost>
```

```bash
sudo apachectl configtest && sudo systemctl reload apache2
```

> `+FollowSymLinks` **wajib** — tanpa itu foto dan berkas unggahan tidak tampil.
>
> `public/.htaccess` sudah ikut dalam repo, jadi tidak perlu disalin manual.

### nginx

```nginx
server {
    server_name desa-anda.id;
    root /var/www/opensid/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/run/php/php8.4-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location /desa/ {
        disable_symlinks off;
    }
}
```

```bash
sudo nginx -t && sudo systemctl reload nginx
```

> Pengguna nginx **tidak perlu** `.htaccess` sama sekali. Dan inilah keuntungan
> terbesar perubahan ini bagi Anda: sebelumnya nginx sama sekali tidak membaca
> `.htaccess`, sehingga `.env` benar-benar terbuka. Sekarang aman tanpa
> konfigurasi tambahan apa pun.

### OpenLiteSpeed

WebAdmin → Virtual Hosts → **General** → Document Root: `$VH_ROOT/public`.
Tab **Security** → Follow Symbolic Link: `Yes`. Lalu **Graceful Restart**.

## C2. Daftar periksa setelah selesai

### Fungsional

- [ ] Halaman depan tampil, tata letak tidak berantakan (CSS termuat)
- [ ] Bisa login ke admin
- [ ] **Form artikel → tombol pilih gambar → file manager terbuka** (`rfm`)
- [ ] Foto artikel & foto penduduk tampil
- [ ] Logo desa tampil di kop surat
- [ ] **Cetak surat**: logo, stempel, logo BSrE muncul; lampiran ber-CSS
- [ ] Captcha muncul di halaman login
- [ ] Halaman peta (GIS) menampilkan ikon lokasi
- [ ] **Pengaturan → Tema**: thumbnail setiap tema tampil dan **berbeda-beda**
- [ ] **Info Sistem** tidak menampilkan peringatan

### Keamanan — lakukan ini!

**Hapus `public/.htaccess` lebih dulu** agar yang teruji struktur foldernya,
bukan aturan web server:

```bash
mv public/.htaccess /tmp/htaccess.bak
```

Semua alamat ini **harus 404 atau 403**:

```
https://host/.env
https://host/composer.json
https://host/vendor/autoload.php
https://host/config/database.php
https://host/desa/config/database.php
https://host/desa/app_key
https://host/desa/themes/<tema>/config.json
https://host/desa/themes/<tema>/resources/views/template.blade.php
https://host/storage/logs
https://host/.git/config
```

Kalau ada satu saja yang menampilkan isi berkas, migrasinya **belum selesai** —
document root masih menunjuk tempat lama.

Sebaliknya, ini **harus 200**:

```
https://host/desa/upload/artikel/<gambar>      (symlink bekerja)
https://host/assets/images/garuda.png
https://host/rfm/plugin.min.js
```

Kembalikan setelah selesai:

```bash
mv /tmp/htaccess.bak public/.htaccess
```

> **Kode status saja tidak cukup.** Halaman error PHP tetap membalas HTTP 200.
> Periksa juga isinya:
> ```bash
> curl -s https://host/ | grep -ciE "Fatal error|Warning:|Failed to open stream"
> # harus 0
> ```

## C3. Pemecahan masalah

### `git pull` gagal: "would be overwritten by merge" pada `.htaccess`

Muncul bila operator pernah mengkustomisasi `.htaccess` **di root proyek**
sendiri, sebelum berkas itu dilacak Git. Pulihkan dengan satu langkah:

```bash
git stash        # simpan versi kustom
git pull
git stash pop    # kembalikan, selesaikan konflik bila ada
```

Atau, bila kustomisasi itu tidak lagi diperlukan (document root sudah/akan
dipindahkan ke `public/`):

```bash
git checkout -- .htaccess && git pull
```

### Semua halaman 404

Document root belum menunjuk `public/`, atau web server belum di-restart.
Bila document root masih di root proyek, pastikan `index.php` dan `.htaccess`
forwarder di root ada (keduanya ikut `git pull`) dan `mod_rewrite` aktif.

### Tampilan berantakan, tanpa warna dan tata letak

Berkas CSS tidak termuat. Pastikan `public/assets/` ada isinya dan `APP_URL`
di `.env` benar.

### Foto dan berkas unggahan tidak tampil

Symlink tidak diikuti web server:

- Apache: `Options +FollowSymLinks`
- nginx: `disable_symlinks off`
- OpenLiteSpeed: Follow Symbolic Link = `Yes`

Periksa symlink-nya ada:

```bash
ls -la public/desa/
ls -la public/storage
```

Harus terlihat tanda panah `->`. Kalau tidak:

```bash
php artisan storage:link --relative
```

Aplikasi juga memasangnya sendiri saat migrasi dijalankan.

> ⚠️ Jika hosting Anda melarang symlink **sepenuhnya** (bukan sekadar
> `SymLinksIfOwnerMatch`), foto tidak akan tampil dan **belum ada penanganan
> otomatis** untuk kondisi ini. Hubungi penyedia hosting untuk mengaktifkan
> symlink, atau pindah ke hosting/VPS yang mengizinkannya.

### Tombol pilih gambar tidak membuka file manager

Periksa `public/rfm/` ada, lalu:

```bash
curl -I https://host/rfm/plugin.min.js   # harus 200
curl -I https://host/rfm/dialog.php      # 401 tanpa login — itu normal
```

401 berarti berjalan benar: penjaga autentikasi bekerja. Yang salah justru
kalau muncul *fatal error*.

### Stempel / logo BSrE hilang dari surat, QR code tanpa logo

Berkas aset tidak ditemukan. Periksa:

```bash
php artisan tinker --execute='printf("%s\n", is_file(public_path(STEMPEL)) ? "ADA" : "HILANG");'
```

Bila `HILANG`, kemungkinan `public/assets/` tidak lengkap.

### Thumbnail tema sama semua atau rusak

Aset tema disajikan lewat route, bukan berkas statis. Uji:

```bash
curl -I "https://host/theme_asset/<slug>?file=thumbnail/preview-1.jpg"   # 200
```

### URL semua berawalan `/index.php/`

`public/.htaccess` hilang. Berkas itu ikut Git, jadi pulihkan:

```bash
git checkout public/.htaccess
```

Pada instalasi produksi yang tidak memakai Git, salin ulang dari repo.

### Peringatan merah "Document root belum diarahkan ke folder public"

Peringatan itu benar. Situs berjalan, tetapi berkas sensitif masih terbuka.
Ikuti [Bagian C1](#c1-mengarahkan-document-root).

### Migrasi mencetak "link already exists"

Tidak terjadi pada versi ini — command hanya dipanggil bila ada link yang
memang belum terpasang. Bila tetap muncul, berarti ada link yang menunjuk
target salah; hapus `public/desa` lalu jalankan ulang `storage:link --relative`.

### Muncul folder `assets/` di root setelah `composer install`

`composer.json` bagian `extra.copy-file` masih memakai tujuan lama. Tujuannya
harus `public/assets/js/tinymce-72`, bukan `assets/js/tinymce-72`.

### Peringatan "lock file is not up to date"

`composer.json` diubah tanpa menyelaraskan `composer.lock`. Perbaiki dengan:

```bash
composer update --lock
```

Perintah ini hanya memperbarui metadata lock, tidak menyentuh versi paket.

---

## Lampiran — Catatan untuk pengembang

### Aturan memilih konstanta path

| Berkas perlu diambil browser lewat URL? | Pakai |
|---|---|
| Ya — `assets/`, `favicon.ico` | `PUBLICPATH` / `public_path()` |
| Tidak — `desa/`, `storage/`, `Modules/`, `vendor/` | `FCPATH` / `base_path()` |

### Dua lapisan kompatibilitas yang masih ada

Keduanya sengaja dipertahankan dan ditandai eksplisit di kode:

1. **`chdir()` pada `bootstrap/bootCodeIgniter.php`** — menyamakan folder kerja
   PHP dengan `FCPATH`, sebagaimana diasumsikan CodeIgniter. Diperlukan oleh 63
   pemakaian path relatif di 30 berkas warisan. Hanya aktif pada konteks
   CodeIgniter; request Laravel murni tidak tersentuh.

2. **Penambatan `assets/` di `default_file()` dan `to_base64()`** — path
   berawalan `assets/` diselesaikan lewat `public_path()`.

Perbaikan akhirnya: tambatkan ke-63 path tersebut ke `FCPATH` satu per satu,
lalu `chdir()` dapat dibuang. Direncanakan sebagai pekerjaan terpisah karena
menyentuh area yang belum punya test otomatis.

### Berkas yang tidak ikut Git

| Berkas | Keterangan |
|---|---|
| `public/desa/*`, `public/storage` | Symlink, dibuat `storage:link` |
| `desa/` | Data milik desa |
| `.e2e.env`, `phpunit.xml` | Konfigurasi lokal |

> `index.php` dan `.htaccess` di **root proyek** kini **ikut Git** (forwarder
> ke `public/`), supaya instalasi lama yang memperbarui lewat `git pull` tetap
> hidup tanpa aksi manual. Pada VPS yang docroot-nya sudah di `public/`, kedua
> berkas ini berada di luar docroot dan tidak pernah dibaca web server.
>
> `.htaccess` root itu **juga menolak akses langsung** ke `vendor/`, `app/`,
> `config/`, `Modules/`, `storage/` non-publik, `desa/config`, `desa/app_key`,
> `desa/themes`, `composer.json`, dan berkas sensitif lain — sebagai proteksi
> transisi selama document root belum dipindahkan. Ini **hanya** ada di
> `.htaccess` root (pasangan `index.php` root); `public/.htaccess` tidak
> memuatnya karena folder-folder itu memang sudah di luar `public/`. Proteksi
> ini bergantung pada web server yang membaca `.htaccess` (Apache/OLS dengan
> `AllowOverride All`); nginx atau `AllowOverride None` tidak terlindungi —
> pindahkan document root ke `public/`.
