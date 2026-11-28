<?php

/*
 * Blok .htaccess untuk menolak eksekusi skrip di folder yang dapat ditulis
 * (unggahan, arsip, storage, assets). Dipakai ulang oleh htaccess1..3 di bawah
 * dan oleh perintah `php artisan opensid:amankan-folder-upload` untuk menimpa
 * berkas .htaccess lama pada instalasi existing.
 *
 * Perbaikan untuk https://github.com/OpenSID/premium/issues/7008:
 * - pola FilesMatch case-insensitive `(?i)` + daftar ekstensi skrip lengkap;
 * - sintaks Apache 2.4 (`Require all denied`) dengan fallback Apache 2.2;
 * - handler & mesin PHP dimatikan (RemoveHandler/RemoveType/SetHandler/engine off)
 *   sehingga file tetap inert walau di-include/symlink/alias;
 * - aturan berbasis mod_rewrite agar tetap berlaku di LiteSpeed/OpenLiteSpeed.
 *
 * Catatan: .htaccess DIABAIKAN sepenuhnya oleh Nginx. Proteksi setara di level
 * web server didokumentasikan di dokumentasi/PANDUAN_PROTEKSI_EKSEKUSI_UPLOAD.md.
 */
$blokirEksekusiSkrip = <<<'APACHE'
    # --- OpenSID: tolak eksekusi skrip di folder ini (premium#7008) ---
    <FilesMatch "(?i)\.(php[0-9]*|phtml|phtm|phps|phpt|phar|pht|pgif|phpjpeg|inc|hphp|ctp|module)\.?$">
        <IfModule mod_authz_core.c>
            Require all denied
        </IfModule>
        <IfModule !mod_authz_core.c>
            Order allow,deny
            Deny from all
        </IfModule>
    </FilesMatch>

    <IfModule mod_rewrite.c>
        RewriteEngine On
        RewriteRule "(?i)\.(php[0-9]*|phtml|phtm|phps|phpt|phar|pht|pgif|phpjpeg|inc|hphp|ctp|module)\.?$" - [F,L]
    </IfModule>

    <IfModule mod_mime.c>
        RemoveHandler .php .php3 .php4 .php5 .php7 .php8 .phtml .phtm .phar .pht .phps .phpt .cgi .pl .py
        RemoveType .php .php3 .php4 .php5 .php7 .php8 .phtml .phtm .phar .pht .phps .phpt .cgi .pl .py
    </IfModule>

    <FilesMatch "(?i)\.(php[0-9]*|phtml|phtm|phps|phpt|phar|pht)\.?$">
        SetHandler None
    </FilesMatch>

    <IfModule mod_php.c>
        php_flag engine off
    </IfModule>
    <IfModule mod_php5.c>
        php_flag engine off
    </IfModule>
    <IfModule mod_php7.c>
        php_flag engine off
    </IfModule>
    <IfModule mod_php8.c>
        php_flag engine off
    </IfModule>

    Options -ExecCGI -Indexes
    APACHE;

/*
 * Tambahan untuk folder arsip/dokumen (htaccess2): tolak juga akses langsung ke
 * berkas dokumen/gambar agar diserahkan lewat controller yang memeriksa hak akses.
 */
$blokirBerkasArsip = <<<'APACHE'
    # --- OpenSID: tolak akses langsung berkas arsip/dokumen (premium#7008) ---
    <FilesMatch "(?i)\.(rtf|pdf|jpe?g|png|gif|webp|bmp|tiff?)$">
        <IfModule mod_authz_core.c>
            Require all denied
        </IfModule>
        <IfModule !mod_authz_core.c>
            Order allow,deny
            Deny from all
        </IfModule>
    </FilesMatch>
    APACHE;

/*
 * Tambahan untuk storage/assets (htaccess3): ekstensi skrip non-PHP lain.
 */
$blokirSkripLain = <<<'APACHE'
    # --- OpenSID: tolak eksekusi skrip non-PHP (premium#7008) ---
    <FilesMatch "(?i)\.(pl|py|jsp|asp|aspx|cgi|sh|bash|htm|shtml|xhtml)$">
        <IfModule mod_authz_core.c>
            Require all denied
        </IfModule>
        <IfModule !mod_authz_core.c>
            Order allow,deny
            Deny from all
        </IfModule>
    </FilesMatch>
    APACHE;

return [
    'desa' => [
        'desa/anjungan/menu/'             => [0775, 'htaccess1'],
        'desa/arsip/'                     => [0775, 'htaccess2'],
        'desa/upload/rtf/'                => [0775, 'htaccess2'],
        'desa/cache/'                     => [0775],
        'desa/config/'                    => [0775],
        'desa/logo/'                      => [0775, 'htaccess1'],
        'desa/pengaturan/'                => [0775, 'htaccess1'],
        'desa/pengaturan/siteman/'        => [0775, 'htaccess1'],
        'desa/pengaturan/siteman/images/' => [0775, 'htaccess1'],
        'desa/template-surat/lampiran/'   => [0775],
        'desa/themes/'                    => [0775],
        'desa/upload/'                    => [0775, 'htaccess1'],
        'desa/upload/fonts/'              => [0775, 'htaccess1', ['vendor/tecnickcom/tcpdf/fonts/helvetica.php']],
        'desa/upload/artikel/'            => [0775, 'htaccess1'],
        'desa/upload/buku_tamu/'          => [0775, 'htaccess1'],
        'desa/upload/dokumen/'            => [0775, 'htaccess2'],
        'desa/upload/galeri/'             => [0775, 'htaccess1'],
        'desa/upload/gis/'                => [0775, 'htaccess1'],
        'desa/upload/gis/area/'           => [0775, 'htaccess1'],
        'desa/upload/gis/garis/'          => [0775, 'htaccess1'],
        'desa/upload/gis/lokasi/'         => [0775, 'htaccess1'],
        'desa/upload/gis/lokasi/point/'   => [0775, 'htaccess1'],
        'desa/upload/media/'              => [0775, 'htaccess1'],
        'desa/upload/pendaftaran/'        => [0775, 'htaccess1'],
        'desa/upload/pengaduan/'          => [0775, 'htaccess1'],
        'desa/upload/pengesahan/'         => [0775, 'htaccess1'],
        'desa/upload/produk/'             => [0775, 'htaccess1'],
        'desa/upload/sinkronisasi/'       => [0775, 'htaccess1'],
        'desa/upload/sosmed/'             => [0775, 'htaccess1'],
        'desa/upload/thumbs/'             => [0775, 'htaccess1'],
        'desa/upload/user_pict/'          => [0775, 'htaccess1'],
        'desa/upload/kelompok/'           => [0775, 'htaccess1'],
        'desa/upload/lembaga/'            => [0775, 'htaccess1'],
        'desa/upload/vaksin/'             => [0775, 'htaccess1'],
        'desa/upload/widgets/'            => [0775, 'htaccess1'],
        'desa/upload/dtks/'               => [0775, 'htaccess1'],
        'desa/upload/pengajuan_izin/'     => [0775, 'htaccess1'],
        'desa/upload/themes/'             => [0775],
        'desa/widgets/'                   => [0775],
    ],

    'lainnya' => [
        'storage/framework/'            => [0775, 'htaccess3'],
        'storage/framework/cache/'      => [0775, 'htaccess3'],
        'storage/framework/cache/data/' => [0775, 'htaccess3'],
        'storage/framework/sessions/'   => [0775, 'htaccess3'],
        'storage/framework/views/'      => [0775, 'htaccess3'],
        'storage/logs/'                 => [0775, 'htaccess3'],
        'storage/app/'                  => [0775, 'htaccess3'],
        'storage/app/public/'           => [0775, 'htaccess3'],
        'storage/debugbar/'             => [0775, 'htaccess3'],
        'backup_inkremental/'           => [0775, 'htaccess3'],
        'public/assets/'                => [0755, 'htaccess3'],
    ],

    'config' => <<<'EOS'
        <?php
        $config['user_admin'] = 0;
        $config['trusted_hosts'] = [];
        EOS,

    'database' => <<<'EOS'
        <?php
        $db['default']['hostname'] = 'localhost';
        $db['default']['username'] = 'root';
        $db['default']['password'] = '';
        $db['default']['port']     = 3306;
        $db['default']['database'] = 'premium';
        $db['default']['dbcollat'] = 'utf8mb4_general_ci';
        $db['default']['stricton'] = true;
        $db['default']['options']  = [
            // PDO::ATTR_EMULATE_PREPARES => true,
        ];
        EOS,

    'index_html' => <<<'EOS'
        <html>
        <head>
            <title>403 Forbidden</title>
        </head>
        <body>
        <p>Directory access is forbidden.</p>
        </body>
        </html>
        EOS,

    'htaccess1' => $blokirEksekusiSkrip . "\n",

    'htaccess2' => $blokirBerkasArsip . "\n\n" . $blokirEksekusiSkrip . "\n",

    'htaccess3' => $blokirSkripLain . "\n\n" . $blokirEksekusiSkrip . "\n",

    'htaccess4' => <<<'EOS'
        <FilesMatch "\.(php)$">
            order allow,deny
            allow from all
        </FilesMatch>
        EOS,

    'siteman_css' => <<<'EOS'
        body.login{
            background-size: cover;
            -webkit-background-size: cover;
            -moz-background-size: cover;
            -o-background-size: cover;
        }
        div.contentm{
            background-color: #f4f4da;
        }
        EOS,

    'siteman_mandiri_css' => <<<'EOS'
        body.login{
            background-size: cover;
            -webkit-background-size: cover;
            -moz-background-size: cover;
            -o-background-size: cover;
        }
        div.contentm{
            background-color: #f4f4da;
        }
        EOS,
];
