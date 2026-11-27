<?php

return [
    'desa' => [
        'desa/anjungan/menu/'             => [0775, 'htaccess1'],
        'desa/arsip/'                     => [0775, 'htaccess2'],
        'desa/upload/rtf/'                => [0775, 'htaccess2'],
        'desa/cache/'                     => [0775],
        'desa/config/'                    => [0775],
        'desa/logo/'                      => [0775, 'htaccess1'],
        'desa/pengaturan/'                => [0775],
        'desa/pengaturan/siteman/'        => [0775],
        'desa/pengaturan/siteman/images/' => [0775],
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
        'assets/'                       => [0755, 'htaccess3'],
        'rfm/'                          => [0755, 'htaccess4'],
        'rfm/config/'                   => [0755, 'htaccess4'],
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
