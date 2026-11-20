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

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     *
     * Header keamanan diset pada objek Response (bukan fungsi native header()),
     * sesuai konvensi middleware Laravel sehingga tetap mudah ditest dan tidak
     * bergantung pada side-effect global.
     */
    public function handle(Request $request, Closure $next): Response
    {
        /** @var Response $response */
        $response = $next($request);

        if (! config('security.enabled')) {
            return $response;
        }

        $headers = $this->getMergedSecurityHeaders();

        foreach ($headers as $key => $value) {
            $value = $this->normalizeHeaderValue($value);

            if ($key === 'Strict-Transport-Security' && ! $request->secure()) {
                continue;
            }

            // Skip Permissions-Policy untuk halaman buku-tamu agar kamera dapat diakses
            if ($key === 'Permissions-Policy' && str_contains($request->path(), 'buku-tamu')) {
                continue;
            }

            $response->headers->set($key, $value, true);
        }

        return $response;
    }

    /**
     * Load konfigurasi security dari tema dan gabungkan dengan konfigurasi bawaan.
     * - Header baru dari tema akan ditambahkan
     * - Header yang sudah ada akan di-append value dari tema
     */
    protected function getMergedSecurityHeaders(): array
    {
        $defaultHeaders = config('security.headers', []);
        $themeHeaders   = $this->loadThemeSecurityConfig();

        foreach ($themeHeaders as $key => $value) {
            if (array_key_exists($key, $defaultHeaders)) {
                $defaultHeaders[$key] = $this->appendHeaderValue($defaultHeaders[$key], $value);
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
    protected function appendHeaderValue(string $existingValue, string $newValue): string
    {
        $existingValue = rtrim($existingValue, '; ');

        return $existingValue . ' ' . $this->normalizeHeaderValue($newValue);
    }

    /**
     * Pastikan value header satu baris dan aman untuk Response header Symfony/Laravel.
     */
    protected function normalizeHeaderValue(string|array $value): string
    {
        if (is_array($value)) {
            $value = implode(' ', $value);
        }

        return trim(preg_replace('/\s+/', ' ', (string) $value) ?? '');
    }

    /**
     * Load konfigurasi security dari tema aktif.
     * Mendukung format PHP array (security.php) atau JSON (security.json).
     */
    protected function loadThemeSecurityConfig(): array
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
            Log::error('Error loading theme security config: ' . $e->getMessage());
        }

        return [];
    }
}
