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
 * Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application for file storage.
    |
    */

    'default' => env('FILESYSTEM_DISK', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Below you may configure as many filesystem disks as necessary, and you
    | may even configure multiple disks for the same driver. Examples for
    | most supported storage drivers are configured here for reference.
    |
    | Supported drivers: "local", "ftp", "sftp", "s3"
    |
    */

    'disks' => [

        'assets' => [
            'driver' => 'local',
            // Ikut pindah ke public/ bersama index.php. Folder desa/ tetap di
            // root proyek, karena itu disk 'desa' di bawah tetap base_path().
            'root'   => public_path('assets'),
            'links'  => 'skip',
        ],

        'desa' => [
            'driver' => 'local',
            'root'   => base_path('desa'),
            'links'  => 'skip',
        ],

        'template' => [
            'driver' => 'local',
            'root'   => storage_path('app/template'),
            'links'  => 'skip',
        ],

        'filemanager' => [
            'driver' => 'local',
            'root' => base_path('desa/upload/media'),
            'url' => rtrim(env('APP_URL', 'http://localhost'), '/') . '/desa/upload/media',
            'visibility' => 'public',
            'throw' => false,
            'report' => false,
        ],

        'filemanager_thumbs' => [
            'driver' => 'local',
            'root' => base_path('desa/upload/thumbs'),
            'url' => rtrim(env('APP_URL', 'http://localhost'), '/') . '/desa/upload/thumbs',
            'visibility' => 'public',
            'throw' => false,
            'report' => false,
        ],

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app/private'),
            'serve' => true,
            'throw' => false,
            'report' => false,
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => rtrim(env('APP_URL', 'http://localhost'), '/').'/storage',
            'visibility' => 'public',
            'throw' => false,
            'report' => false,
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
            'throw' => false,
            'report' => false,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    |
    | Here you may configure the symbolic links that will be created when the
    | `storage:link` Artisan command is executed. The array keys should be
    | the locations of the links and the values should be their targets.
    |
    */

    /*
     * Folder desa/ sengaja TIDAK dipindah ke public/ karena merupakan data milik
     * desa: di-gitignore penuh dan tidak boleh tersentuh saat update aplikasi.
     * Symlink membuat URL lama seperti desa/upload/artikel/foto.jpg tetap valid
     * tanpa memindahkan satu berkas pun.
     *
     * Hanya folder yang memang perlu diakses browser yang didaftarkan di sini.
     * desa/config, desa/app_key, desa/cache, desa/arsip, desa/template-surat,
     * dan desa/widgets sengaja TIDAK disertakan — justru itulah berkas yang
     * selama ini terekspos dan menjadi alasan pemindahan ke public/.
     *
     * desa/themes juga TIDAK disymlink. Folder tema berisi kode sumber —
     * berkas .blade.php, config.json, dan composer.json — yang tidak boleh
     * terbaca dari internet. Aset tema disajikan lewat route theme_asset,
     * yang membaca dari <path tema>/assets saja, sehingga berlaku sama untuk
     * tema desa (desa/themes) maupun tema sistem (storage/app/themes).
     * Seluruh tema memakai helper theme_asset(); tidak ada yang menuliskan
     * URL desa/themes secara langsung.
     *
     * Dibuat dengan `php artisan storage:link --relative` agar target bersifat
     * relatif sehingga tetap valid bila folder proyek dipindahkan.
     */
    'links' => [
        public_path('desa/upload')     => base_path('desa/upload'),
        public_path('desa/logo')       => base_path('desa/logo'),
        public_path('desa/pengaturan') => base_path('desa/pengaturan'),
        public_path('desa/anjungan')   => base_path('desa/anjungan'),
    ],

];
