# Panduan Proteksi Eksekusi Skrip di Folder Unggahan

Referensi: [premium#7008](https://github.com/OpenSID/premium/issues/7008)

OpenSID menyimpan berkas unggahan di dalam _document root_ (`desa/upload/`,
`assets/`, `storage/`). Bila sebuah berkas berbahaya lolos validasi (polyglot,
bug validasi, atau akses File Manager lewat kebocoran `APP_KEY`), satu-satunya
lapisan yang mencegahnya menjadi _webshell_/RCE adalah aturan **"jangan eksekusi
PHP di folder ini"**.

Sampai premium#7008, aturan itu hanya berupa `.htaccess` dengan pola `FilesMatch`
yang:

- **case-sensitive** — `shell.PHP` (huruf besar) lolos, tetapi tetap dieksekusi
  Apache sebagai PHP;
- tidak memblokir `.php4 .php5 .php7 .php8 .pht .phps .phar .phtml` dll.;
- memakai sintaks Apache 2.2 (`Order Allow,Deny`) yang bisa memicu error di
  Apache 2.4 tanpa `mod_access_compat`;
- **tidak mematikan mesin PHP** (tidak ada `engine off` / `SetHandler None`);
- **diabaikan sepenuhnya oleh Nginx** dan sebagian oleh OpenLiteSpeed.

## Yang sudah diperbaiki di aplikasi

1. Template `htaccess1`, `htaccess2`, `htaccess3` di
   [`config/installer.php`](../config/installer.php) diganti dengan versi keras:
   pola `(?i)` case-insensitive + daftar ekstensi lengkap, `Require all denied`
   (Apache 2.4) dengan _fallback_ `Order allow,deny`, `RemoveHandler`/`RemoveType`,
   `SetHandler None`, `php_flag engine off` untuk mod_php 5/7/8, aturan
   `mod_rewrite` (berlaku di LiteSpeed/OpenLiteSpeed), serta
   `Options -ExecCGI -Indexes`.
2. Helper `folder()` kini **memperbarui** `.htaccess` lama yang masih memakai
   template lemah (dikenali dari tidak adanya penanda `premium#7008`), bukan
   hanya menulis saat berkas belum ada.
3. Perintah artisan untuk menyapu instalasi _existing_:

   ```bash
   php artisan opensid:amankan-folder-upload --dry-run   # pratinjau
   php artisan opensid:amankan-folder-upload             # terapkan
   ```

   Perintah menimpa semua `.htaccess` di folder terdaftar pada
   `config/installer.php` **dan** hasil sapuan rekursif pada `desa/upload/`,
   `assets/`, `storage/app/` (mis. subfolder tanggal). Folder `rfm/` (template
   `htaccess4`) sengaja tidak disentuh karena butuh eksekusi PHP.

> **Jalankan `php artisan opensid:amankan-folder-upload` setiap kali memperbarui
> OpenSID** — atau tambahkan ke skrip _deploy_.

## Wajib: proteksi di level web server

`.htaccess` **tidak berlaku di Nginx** dan hanya sebagian di OpenLiteSpeed.
Tambahkan aturan berikut pada konfigurasi server. Aturan ini adalah pertahanan
utama, bukan pelengkap.

### Nginx

Letakkan di dalam blok `server { ... }`, **sebelum** `location ~ \.php$` yang
umum:

```nginx
# Tolak semua permintaan skrip di folder yang dapat ditulis.
location ~* ^/(desa/upload|assets|storage)/.*\.(php[0-9]*|phtml?|phtm|phps|phpt|phar|pht|pl|py|cgi|sh|asp|aspx|jsp)$ {
    deny all;
    return 403;
}

# Batasi eksekusi PHP hanya ke root aplikasi + index.php.
location ~ \.php$ {
    try_files $uri =404;
    fastcgi_split_path_info ^(.+\.php)(/.+)$;
    fastcgi_pass   unix:/run/php/php8.2-fpm.sock;
    fastcgi_index  index.php;
    include        fastcgi_params;
    fastcgi_param  SCRIPT_FILENAME $document_root$fastcgi_script_name;
}
```

Bila memungkinkan, sajikan folder unggahan sebagai statis murni:

```nginx
location ^~ /desa/upload/ {
    location ~ \.php { deny all; return 403; }
}
```

### OpenLiteSpeed

Pada **Virtual Host → Context**, tambahkan _Static Context_ untuk `/desa/upload/`,
`/assets/`, `/storage/` dengan:

- **Accessible:** Yes
- **Enable Script:** **No**
- **Restrained:** Yes

Atau lewat `.htaccess` (didukung OLS bila `Rewrite` aktif) — template hasil
premium#7008 sudah menyertakan blok `mod_rewrite` `RewriteRule ... [F,L]` yang
dihormati OLS.

### Apache

Pastikan `AllowOverride` mengizinkan `FileInfo Options=Indexes,ExecCGI Limit`
(atau `All`) pada direktori aplikasi agar template `.htaccess` berlaku penuh.
Idealnya ganti dengan blok `<Directory>` setara di konfigurasi vhost sehingga
tidak bergantung pada `.htaccess`.

## Rencana lanjutan (di luar cakupan premium#7008)

- Menyimpan berkas unggahan **di luar** _document root_ dan menyajikannya lewat
  controller (`readfile` + header aman + cek hak akses), minimal untuk tipe
  non-gambar.
