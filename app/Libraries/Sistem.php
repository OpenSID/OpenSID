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

namespace App\Libraries;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

defined('BASEPATH') || exit('No direct script access allowed');

class Sistem
{
    public static function cekEkstensi(): array
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
        $requirements = [
            ['key' => 'max_execution_time', 'condition' => '>=', 'required' => '300'],
            ['key' => 'post_max_size', 'condition' => '>=', 'required' => '10M'],
            ['key' => 'upload_max_filesize', 'condition' => '>=', 'required' => '20M'],
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
        $wajib    = ['symlink'];
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
