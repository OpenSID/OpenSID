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
 * Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

namespace App\Libraries;

use Illuminate\Support\Facades\DB;

defined('BASEPATH') || exit('No direct script access allowed');

// TODO : hapus constanta di model setting_model sebelumnya, saat ini belum dihapus karna masih digunakan di modul lain
define('EKSTENSI_WAJIB', serialize([
    'curl',
    'fileinfo',
    'gd',
    'iconv',
    'json',
    'mbstring',
    'mysqli',
    'mysqlnd',
    'tidy',
    'zip',
    'exif',
]));
define('minPhpVersion', '7.4.0');
define('maxPhpVersion', '8.2.0');
define('minMySqlVersion', '5.6.0');
define('maxMySqlVersion', '8.0.0');
define('minMariaDBVersion', '10.3.0');

class Sistem
{
    public static function cekEkstensi()
    {
        $e = get_loaded_extensions();
        usort($e, 'strcasecmp');
        $ekstensi = array_flip($e);
        $e        = unserialize(EKSTENSI_WAJIB);
        usort($e, 'strcasecmp');
        $ekstensi_wajib = array_flip($e);
        $lengkap        = true;

        foreach (array_keys($ekstensi_wajib) as $key) {
            $ekstensi_wajib[$key] = isset($ekstensi[$key]);
            $lengkap              = $lengkap && $ekstensi_wajib[$key];
        }
        $data['lengkap']  = $lengkap;
        $data['ekstensi'] = $ekstensi_wajib;

        return $data;
    }

    public static function cekKebutuhanSistem(): array
    {
        $data = [];

        $sistem = [
            ['max_execution_time', '>=', '300'],
            ['post_max_size', '>=', '10M'],
            ['upload_max_filesize', '>=', '20M'],
            ['memory_limit', '>=', '512M'],
        ];

        foreach ($sistem as $value) {
            [$key, $kondisi, $val] = $value;

            $data[$key] = [
                'v'      => $val,
                $key     => ini_get($key),
                'result' => version_compare(ini_get($key), $val, $kondisi),
            ];
        }

        return $data;
    }

    public static function cekPhp(): array
    {
        return [
            'versi' => PHP_VERSION,
            'cek'   => (version_compare(PHP_VERSION, minPhpVersion, '>=') && version_compare(PHP_VERSION, maxPhpVersion, '<=')),
        ];
    }

    public static function cekDatabase(): array
    {
        $versi = DB::select('SELECT VERSION() AS version')[0]->version;

        return [
            'versi' => $versi,
            'cek'   => (version_compare($versi, minMySqlVersion, '>=') && version_compare($versi, maxMySqlVersion, '<')) || (version_compare($versi, minMariaDBVersion, '>=')),
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
