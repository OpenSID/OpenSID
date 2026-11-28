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

namespace App\Libraries;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

defined('BASEPATH') || exit('No direct script access allowed');

class Sistem
{
    public static function daftarEkstensiWajib(): array
    {
        return [
            'pdo_mysql'      => ['name' => 'PDO MySQL extension'],
            'curl'           => ['name' => 'Curl extension'],
            'exif'           => ['name' => 'Exif extension'],
            'fileinfo'       => ['name' => 'Fileinfo extension'],
            'gd'             => ['name' => 'GD extension'],
            'iconv'          => ['name' => 'Iconv extension'],
            'json'           => ['name' => 'Json extension'],
            'mbstring'       => ['name' => 'Mbstring extension'],
            'mysqli'         => ['name' => 'Mysqli extension'],
            'mysqlnd'        => ['name' => 'Mysqlnd extension'],
            'tidy'           => ['name' => 'Tidy extension'],
            'zip'            => ['name' => 'Zip extension'],
        ];
    }

    public static function syaratPhp(): array
    {
        return [
            'name' => 'PHP Version',
            'min'  => '8.4.0',
            'max'  => '8.4.99',
        ];
    }

    public static function syaratDatabase(): array
    {
        return [
            'mysql' => [
                'name' => 'MySQL',
                'min'  => '5.6.0',
                'max'  => '8.4.0',
            ],
            'mariadb' => [
                'name' => 'MariaDB',
                'min'  => '10.3.0',
                'max'  => '11.99.99',
            ],
        ];
    }

    public static function cekEkstensi(): array
    {
        $loaded = array_flip(get_loaded_extensions());
        $devEnv = ! app()->environment('production');
        $daftar = self::daftarEkstensiWajib();

        $ekstensi = [];
        $lengkap  = true;

        foreach ($daftar as $key => $meta) {
            $devOptional = ! empty($meta['dev_optional']);
            $isLoaded    = isset($loaded[$key]);
            $devBypassed = $devOptional && $devEnv && ! $isLoaded;

            $ekstensi[$key] = [
                'loaded'       => $isLoaded,
                'dev_optional' => $devOptional,
                'dev_bypassed' => $devBypassed,
            ];

            if (! $isLoaded && ! $devBypassed) {
                $lengkap = false;
            }
        }

        return [
            'lengkap'  => $lengkap,
            'ekstensi' => $ekstensi,
        ];
    }

    public static function cekKebutuhanSistem(): array
    {
        $requirements = [
            ['key' => 'max_execution_time', 'condition' => '>=', 'required' => '300'],
            ['key' => 'post_max_size', 'condition' => '>=', 'required' => '256M'],
            ['key' => 'upload_max_filesize', 'condition' => '>=', 'required' => '256M'],
            ['key' => 'memory_limit', 'condition' => '>=', 'required' => '512M'],
        ];

        $results = [];

        foreach ($requirements as $requirement) {
            $key           = $requirement['key'];
            $condition     = $requirement['condition'];
            $requiredValue = $requirement['required'];

            // Get the current value of the PHP directive
            $currentValue = ini_get($key);

            if ($currentValue === false) {
                $results[$key] = [
                    'required' => $requiredValue,
                    'current'  => 'Not Available',
                    'result'   => false,
                ];

                continue;
            }

            // Convert size values (e.g., 10M) to bytes for comparison
            $requiredInBytes = Str::convertToBytes($requiredValue);
            $currentInBytes  = Str::convertToBytes($currentValue);

            // Compare the values
            $comparisonResult = version_compare($currentInBytes, $requiredInBytes, $condition);

            $results[$key] = [
                'required' => $requiredValue,
                'current'  => $currentValue,
                'result'   => $comparisonResult,
            ];
        }

        return $results;
    }

    public static function cekPhp(): array
    {
        $syarat = self::syaratPhp();

        return [
            'versi'  => PHP_VERSION,
            'cek'    => version_compare(PHP_VERSION, $syarat['min'], '>=') && version_compare(PHP_VERSION, $syarat['max'], '<='),
            'syarat' => $syarat,
        ];
    }

    public static function cekDatabase(): array
    {
        $versi  = DB::select('SELECT VERSION() AS version')[0]->version;
        $syarat = self::syaratDatabase();

        // MariaDB menyertakan string "MariaDB" di dalam versi-nya
        $isMariaDB = stripos($versi, 'mariadb') !== false;

        // Ekstrak hanya angka versi (misal: "8.0.30" atau "10.6.12-MariaDB")
        $versiAngka = preg_replace('/[^0-9.].*/', '', $versi);

        if ($isMariaDB) {
            $cek = version_compare($versiAngka, $syarat['mariadb']['min'], '>=')
                && version_compare($versiAngka, $syarat['mariadb']['max'], '<');
        } else {
            $cek = version_compare($versiAngka, $syarat['mysql']['min'], '>=')
                && version_compare($versiAngka, $syarat['mysql']['max'], '<');
        }

        return [
            'versi'  => $versi,
            'cek'    => $cek,
            'syarat' => $syarat,
        ];
    }

    public static function disableFunctions(): array
    {
        $wajib    = [];
        $disabled = explode(',', ini_get('disable_functions'));

        $functions = [];
        $lengkap   = true;

        foreach ($wajib as $fuc) {
            $functions[$fuc] = ! in_array($fuc, $disabled);
            $lengkap         = $lengkap && $functions[$fuc];
        }

        $data['lengkap']   = $lengkap;
        $data['functions'] = $functions;

        return $data;
    }
}
