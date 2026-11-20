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

$config = [

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

    'server' => (static function (): array {
        $syaratPhp = App\Libraries\Sistem::syaratPhp();

        $server = [
            'php' => [
                'name'    => $syaratPhp['name'],
                'version' => ">= {$syaratPhp['min']}, <= {$syaratPhp['max']}",
                'check'   => static fn (): bool => version_compare(PHP_VERSION, $syaratPhp['min'], '>=') && version_compare(PHP_VERSION, $syaratPhp['max'], '<='),
            ],
        ];

        foreach (App\Libraries\Sistem::daftarEkstensiWajib() as $ext => $meta) {
            $devOptional    = ! empty($meta['dev_optional']);
            $entry          = ['name' => $meta['name']];
            $entry['check'] = $devOptional
                ? static fn (): bool => ! app()->environment('production') || extension_loaded($ext)
                : static fn (): bool => extension_loaded($ext);

            if ($devOptional) {
                $entry['dev_check'] = static fn (): bool => extension_loaded($ext);
            }

            $server[$ext] = $entry;
        }

        return $server;
    })(),
];
