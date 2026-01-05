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

namespace App\Http\Middleware;

use Throwable;

class SecurityHeaders
{
    public static function handle()
    {
        if (! config('security.enabled')) {
            return;
        }

        $headers = self::getMergedSecurityHeaders();

        foreach ($headers as $key => $value) {

            if ($key === 'Strict-Transport-Security' && ! is_https()) {
                continue;
            }

            header("{$key}: {$value}", true);
        }
    }

    /**
     * Load konfigurasi security dari tema dan gabungkan dengan konfigurasi bawaan.
     * - Header baru dari tema akan ditambahkan
     * - Header yang sudah ada akan di-append value dari tema
     */
    protected static function getMergedSecurityHeaders(): array
    {
        $defaultHeaders = config('security.headers', []);
        $themeHeaders   = self::loadThemeSecurityConfig();

        foreach ($themeHeaders as $key => $value) {
            if (array_key_exists($key, $defaultHeaders)) {
                $defaultHeaders[$key] = self::appendHeaderValue($defaultHeaders[$key], $value);
            } else {
                $defaultHeaders[$key] = $value;
            }
        }

        return $defaultHeaders;
    }

    /**
     * Append value tema ke value header yang sudah ada.
     *
     * @param string $existingValue Value header yang sudah ada
     * @param string $newValue      Value baru dari tema
     */
    protected static function appendHeaderValue(string $existingValue, string $newValue): string
    {
        $existingValue = rtrim($existingValue, '; ');

        return $existingValue . ' ' . $newValue;
    }

    /**
     * Load konfigurasi security dari tema aktif.
     * Mendukung format PHP array (security.php) atau JSON (security.json).
     */
    protected static function loadThemeSecurityConfig(): array
    {
        try {
            $themePath = theme_full_path();

            if (empty($themePath)) {
                return [];
            }

            $themeFilePhp  = base_path($themePath . '/security.php');
            $themeFileJson = base_path($themePath . '/security.json');

            if (file_exists($themeFilePhp)) {
                $config = include $themeFilePhp;

                return is_array($config) ? $config : [];
            }

            if (file_exists($themeFileJson)) {
                $config = json_decode(file_get_contents($themeFileJson), true);

                return is_array($config) ? $config : [];
            }
        } catch (Throwable $e) {
            if (function_exists('log_message')) {
                log_message('error', 'Error loading theme security config: ' . $e->getMessage());
            }
        }

        return [];
    }
}
