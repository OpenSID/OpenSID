<?php

/*
 *
 * File ini bagian dari:
 *
 * OpenSID
 *
 * Sistem informasi desa sumber terbuka untuk memajukan desa
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 *
 * Dengan ini diberikan izin, secara gratis, kepada siapa pun yang mendapatkan salinan
 * dari perangkat lunak ini dan file dokumentasi terkait ("Aplikasi Ini"), untuk diperlakukan
 * tanpa batasan, termasuk hak untuk menggunakan, menyalin, mengubah dan/atau mendistribusikan,
 * asal tunduk pada syarat berikut:
 *
 * Pemberitahuan hak cipta di atas dan pemberitahuan izin ini harus disertakan dalam
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau menghilangkan
 * pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.
 *
 * PERANGKAT LUNAK INI DISEDIAKAN "SEBAGAIMANA ADANYA", TANPA JAMINAN APA PUN, BAIK TERSURAT MAUPUN
 * TERSIRAT. PENULIS ATAU PEMEGANG HAK CIPTA SAMA SEKALI TIDAK BERTANGGUNG JAWAB ATAS KLAIM, KERUSAKAN ATAU
 * KEWAJIBAN APAPUN ATAS PENGGUNAAN ATAU LAINNYA TERKAIT APLIKASI INI.
 *
 * @package   OpenSID
 * @author    Tim Pengembang OpenDesa
 * @copyright Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

$config = [
    'desa' => [
        LOKASI_ICON_MENU_ANJUNGAN  => [0775, 'htaccess1'],
        LOKASI_ARSIP               => [0775, 'htaccess2'],
        LOKASI_CACHE               => [0775],
        LOKASI_CONFIG_DESA         => [0775],
        LOKASI_LOGO_DESA           => [0775, 'htaccess1'],
        'desa/pengaturan/'         => [0775],
        'desa/pengaturan/siteman/' => [0775],
        LATAR_LOGIN                => [0775],
        LOKASI_LAMPIRAN_SURAT_DESA => [0775],
        LOKASI_TEMA_DESA           => [0775],
        LOKASI_UPLOAD              => [0775, 'htaccess1'],
        LOKASI_FONT_DESA           => [0775, 'htaccess1', ['vendor/tecnickcom/tcpdf/fonts/helvetica.php']],
        LOKASI_FOTO_ARTIKEL        => [0775, 'htaccess1'],
        LOKASI_FOTO_BUKU_TAMU      => [0775, 'htaccess1'],
        LOKASI_DOKUMEN             => [0775, 'htaccess2'],
        LOKASI_GALERI              => [0775, 'htaccess1'],
        'desa/upload/gis/'         => [0775, 'htaccess1'],
        LOKASI_FOTO_AREA           => [0775, 'htaccess1'],
        LOKASI_FOTO_GARIS          => [0775, 'htaccess1'],
        LOKASI_FOTO_LOKASI         => [0775, 'htaccess1'],
        LOKASI_SIMBOL_LOKASI       => [0775, 'htaccess1'],
        LOKASI_MEDIA               => [0775, 'htaccess1'],
        'desa/upload/pendaftaran/' => [0775, 'htaccess1'],
        LOKASI_PENGADUAN           => [0775, 'htaccess1'],
        LOKASI_PENGESAHAN          => [0775, 'htaccess1'],
        LOKASI_PRODUK              => [0775, 'htaccess1'],
        LOKASI_SINKRONISASI_ZIP    => [0775, 'htaccess1'],
        LOKASI_SINERGI_PROGRAM     => [0775, 'htaccess1'],
        LOKASI_ICON_SOSMED         => [0775, 'htaccess1'],
        LOKASI_SINERGI_PROGRAM     => [0775, 'htaccess1'],
        'desa/upload/thumbs/'      => [0775, 'htaccess1'],
        LOKASI_USER_PICT           => [0775, 'htaccess1'],
        LOKASI_FOTO_KELOMPOK       => [0775, 'htaccess1'],
        LOKASI_FOTO_LEMBAGA        => [0775, 'htaccess1'],
        LOKASI_VAKSIN              => [0775, 'htaccess1'],
        LOKASI_GAMBAR_WIDGET       => [0775, 'htaccess1'],
        LOKASI_FOTO_DTKS           => [0775, 'htaccess1'],
        LOKASI_WIDGET              => [0775],
        CONFIG_THEMES              => [0775],
    ],

    'lainnya' => [
        'storage/framework/'         => [0775, 'htaccess3'],
        'storage/framework/views/'   => [0775, 'htaccess3'],
        'storage/framework/cache/'   => [0775, 'htaccess3'],
        'storage/logs/'              => [0775, 'htaccess3'],
        'backup_inkremental/'        => [0775, 'htaccess3'],
        'assets/'                    => [0755, 'htaccess3'],
        'assets/kelola_file/'        => [0755, 'htaccess4'],
        'assets/kelola_file/config/' => [0755, 'htaccess4'],
    ],

    'config' => <<<'EOS'
        <?php
        // ----------------------------------------------------------------------------
        // Konfigurasi aplikasi dalam berkas ini merupakan setting konfigurasi tambahan
        // SID. Letakkan setting konfigurasi ini di desa/config/config.php.
        // ----------------------------------------------------------------------------

        // Uncomment jika situs ini untuk demo. Pada demo, user admin tidak bisa dihapus
        // dan username/password tidak bisa diubah

        // $config['demo_mode'] = true;

        // Setting ini untuk menentukan user yang dipercaya. User dengan id di setting ini
        // dapat membuat artikel berisi video yang aktif ditampilkan di Web.
        // Misalnya, ganti dengan id = 1 jika ingin membuat pengguna admin sebagai pengguna terpecaya.
        $config['user_admin'] = 0;

        // Untuk menghindari masalah keamanan, Anda mungkin ingin mengonfigurasi daftar "host tepercaya".
        // Contoh: ['localhost', 'my-development.com', 'my-production.com', 'subdomain.domain.com']
        $config['trusted_hosts'] = [];

        EOS,

    'database' => <<<'EOS'
        <?php
        // -------------------------------------------------------------------------
        //
        // Letakkan username, password dan database sebetulnya di file ini.
        // File ini JANGAN di-commit ke GIT. TAMBAHKAN di .gitignore
        // -------------------------------------------------------------------------

        // Data Konfigurasi MySQL yang disesuaikan

        $db['default']['hostname'] = 'localhost';
        $db['default']['username'] = 'root';
        $db['default']['password'] = '';
        $db['default']['port']     = 3306;
        $db['default']['database'] = 'premium';
        $db['default']['dbcollat'] = 'utf8mb4_general_ci';

        /*
        | Untuk setting koneksi database 'Strict Mode'
        | Sesuaikan dengan ketentuan hosting
        */
        $db['default']['stricton'] = TRUE;
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

    'htaccess1' => <<<'EOS'
        <FilesMatch "\.(php|php\.|php3?|phtml|phpjpeg)$">
            Order Allow,Deny
            Deny from all
        </FilesMatch>
        EOS,

    'htaccess2' => <<<'EOS'
        <FilesMatch "\.(rtf|pdf|jpe?g|png|php|php\.|php3?|phtml|phpjpeg)$">
            Order Allow,Deny
            Deny from all
        </FilesMatch>
        EOS,

    'htaccess3' => <<<'EOS'
        <FilesMatch "\.(php|php\.|php3?|phtml|phpjpeg|pl|py|jsp|asp|htm|shtml|sh|cgi)$">
            order allow,deny
            deny from all
        </FilesMatch>

        EOS,

    'htaccess4' => <<<'EOS'
        <FilesMatch "\.(php)$">
            order allow,deny
            allow from all
        </FilesMatch>
        EOS,

    'siteman_css' => <<<'EOS'
        /*
        * File ini berisi CSS ubahan desa untuk tampilan siteman
        * Letakkan file ini di: desa/css/siteman.css
        */

        /* Sebagai contoh:
        * - Perubahan css di bawah memungkinkan penggunaan gambar untuk latar belakang halaman siteman.
        *   Gambar yang digunakan harus ditempatkan di desa/css/images/latar_login.jpg
        *
        */

        /*
        * Ubah latar login/siteman pd halaman admin Pengaturan > Aplikasi, upload dan ganti latar login sesuia yg diinginkan.
        *
        */
        body.login{
            background-size: cover;
            -webkit-background-size: cover;
            -moz-background-size: cover;
            -o-background-size: cover;
        }
        /* Contoh mengubah warna latar belakang deretan tombol menu utama
        */
        div.contentm{
            background-color: #f4f4da; /* misalnya coba ganti menjadi #c8f1c8 */
        }
        EOS,

    'siteman_mandiri_css' => <<<'EOS'
        /*
        * File ini berisi CSS ubahan desa untuk tampilan siteman
        * Letakkan file ini di: desa/css/siteman.css
        */

        /* Sebagai contoh:
        * - Perubahan css di bawah memungkinkan penggunaan gambar untuk latar belakang halaman siteman.
        *   Gambar yang digunakan harus ditempatkan di desa/css/images/latar_login.jpg
        *
        */

        /*
        * Ubah latar login/siteman pd halaman admin Pengaturan > Aplikasi, upload dan ganti latar login sesuia yg diinginkan.
        *
        */
        body.login{
            background-size: cover;
            -webkit-background-size: cover;
            -moz-background-size: cover;
            -o-background-size: cover;
        }
        /* Contoh mengubah warna latar belakang deretan tombol menu utama
        */
        div.contentm{
            background-color: #f4f4da; /* misalnya coba ganti menjadi #c8f1c8 */
        }
        EOS,

    'folders' => [
        'storage.framework' => [
            'name'  => STORAGEPATH . 'framework',
            'check' => static fn (): bool => substr(sprintf('%o', fileperms(STORAGEPATH . 'framework')), -4) >= '0755',
        ],
        'storage.logs' => [
            'name'  => STORAGEPATH . 'logs',
            'check' => static fn (): bool => substr(sprintf('%o', fileperms(STORAGEPATH . 'logs')), -4) >= '0755',
        ],
        'backup_inkremental' => [
            'name'  => BACKUPPATH,
            'check' => static fn (): bool => is_dir(BACKUPPATH) ? substr(sprintf('%o', fileperms(BACKUPPATH)), -4) >= '0755' : true,
        ],
    ],

    'server' => [
        'php' => [
            'name'    => 'PHP Version',
            'version' => '>= 8.1.0 | <= 8.2.0',
            'check'   => static fn (): bool => version_compare(PHP_VERSION, '8.1', '>=') && version_compare(PHP_VERSION, '8.2', '<='),
        ],
        'pdo' => [
            'name'  => 'PDO',
            'check' => static fn (): bool => extension_loaded('pdo_mysql'),
        ],
        'curl' => [
            'name'  => 'Curl extention',
            'check' => static fn (): bool => extension_loaded('curl'),
        ],
        'fileinfo' => [
            'name'  => 'Fileinfo extension',
            'check' => static fn (): bool => extension_loaded('fileinfo'),
        ],
        'gd' => [
            'name'  => 'GD extension',
            'check' => static fn (): bool => extension_loaded('gd'),
        ],
        'iconv' => [
            'name'  => 'Iconv extension',
            'check' => static fn (): bool => extension_loaded('iconv'),
        ],
        'json' => [
            'name'  => 'Json extension',
            'check' => static fn (): bool => extension_loaded('json'),
        ],
        'mbstring' => [
            'name'  => 'Mbstring extension',
            'check' => static fn (): bool => extension_loaded('mbstring'),
        ],
        'mysqli' => [
            'name'  => 'Mysqli extension',
            'check' => static fn (): bool => extension_loaded('mysqli'),
        ],
        'mysqlnd' => [
            'name'  => 'Mysqlnd extension',
            'check' => static fn (): bool => extension_loaded('mysqlnd'),
        ],
        'tidy' => [
            'name'  => 'Tidy extension',
            'check' => static fn (): bool => extension_loaded('tidy'),
        ],
        'zip' => [
            'name'  => 'Zip extension',
            'check' => static fn (): bool => extension_loaded('zip'),
        ],
        'exif' => [
            'name'  => 'Exif extension',
            'check' => static fn (): bool => extension_loaded('exif'),
        ],
        'symlink' => [
            'name'  => 'Symlink Support',
            'check' => static fn (): bool => function_exists('symlink'),
        ],
    ],
];
