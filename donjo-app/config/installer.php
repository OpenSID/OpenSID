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
 * Hak Cipta 2016 - 2023 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2023 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

$config = [
    'desa' => [
        LOKASI_ARSIP               => [0775, 'htaccess2'],
        LOKASI_CACHE               => [0775],
        LOKASI_CONFIG_DESA         => [0775],
        LOKASI_LOGO_DESA           => [0775, 'htaccess1'],
        'desa/pengaturan/'         => [0775],
        'desa/pengaturan/siteman/' => [0775],
        LATAR_LOGIN                => [0775],
        LOKASI_SURAT_DESA          => [0775],
        LOKASI_LAMPIRAN_SURAT_DESA => [0775],
        LOKASI_TEMA_DESA           => [0775],
        LOKASI_UPLOAD              => [0775, 'htaccess1'],
        LOKASI_FOTO_ARTIKEL        => [0775, 'htaccess1'],
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
        'desa/upload/thumbs/'      => [0775, 'htaccess1'],
        LOKASI_USER_PICT           => [0775, 'htaccess1'],
        LOKASI_VAKSIN              => [0775, 'htaccess1'],
        LOKASI_GAMBAR_WIDGET       => [0775, 'htaccess1'],
        LOKASI_FOTO_DTKS           => [0775, 'htaccess1'],
        LOKASI_WIDGET              => [0775],
    ],

    'lainnya' => [
        'storage/framework/'  => [0775],
        'storage/logs/'       => [0775],
        'backup_inkremental/' => [0775],
        'assets/'             => [0755, 'htaccess3'],
        'assets/filemanager/' => [0755, 'htaccess4'],
    ],

    'config' => <<<'EOS'
        <?php
        // ----------------------------------------------------------------------------
        // Konfigurasi aplikasi dalam berkas ini merupakan setting konfigurasi tambahan
        // SID. Letakkan setting konfigurasi ini di desa/config/config.php.
        // ----------------------------------------------------------------------------

        // Uncomment jika situs ini untuk demo. Pada demo, user admin tidak bisa dihapus
        // dan username/password tidak bisa diubah

        // $config['demo_mode'] = false;

        // Setting ini untuk menentukan user yang dipercaya. User dengan id di setting ini
        // dapat membuat artikel berisi video yang aktif ditampilkan di Web.
        // Misalnya, ganti dengan id = 1 jika ingin membuat pengguna admin sebagai pengguna terpecaya.
        $config['user_admin'] = 0;


        // config email
        $config['protocol']       = 'smtp';  // mail	mail, sendmail, or smtp	The mail sending protocol.
        $config['smtp_host']      = '';      // SMTP Server Address.
        $config['smtp_user']      = '';      // SMTP Username.
        $config['smtp_pass']      = '';      // SMTP Password.
        $config['smtp_port']      = '';      // SMTP Port."
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
        $db['default']['database'] = 'umum';

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

    'offline_mode' => <<<'EOS'
        <!DOCTYPE html>
        <html>
        <head>
            <title>Offline Mode - <?= ucwords($this->setting->sebutan_desa).' '.$main['nama_desa'] ?></title>
            <link rel="shortcut icon" href="<?= favico_desa() ?>"/>
        </head>
        <body>
            <br/><br/><br/>
            <div align="center">
                <img class="profile-user-img img-responsive img-circle" src="<?= gambar_desa($main['logo']); ?>" alt="Logo">
                <p>
                    Selamat datang di Halaman Situs Resmi <?= ucwords($this->setting->sebutan_desa).' '.$main['nama_desa'] ?><br/>
                    Kami mohon maaf untuk sementara halaman tidak dapat di akses, dikarenakan sedang adanya perbaikan oleh tim terkait.
                </p>
                <p>
                    Jika ada keperluan yang mendesak silakan langsung datang ke Kantor <?= ucwords($this->setting->sebutan_desa)?>.<br>
                    Alamat : <?= $main['alamat_kantor'] ?><br>
                    Email : <?= $main['email_desa'] ?><br>
                    Telepon : <?= $main['telepon'] ?>
                </p>
                <p>
                    <?= ucwords($pamong_kades['jabatan']).' '.$main['nama_desa'] ?>
                    <br>
                    <br>
                    <br>
                    <u><b><?= $main['nama_kepala_desa'] ?></b></u><br>
                    NIP. <?= $main['nip_kepala_desa'] ?>
                </p>
            </div>
        </body>
        </html>
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
];
