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

use App\Events\TooManyRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use OpenSID\MiddlewareInterface;

class ThrottleRequests implements MiddlewareInterface
{
    /**
     * Indicates if the rate limiter keys should be hashed.
     */
    protected static $shouldHashKeys = true;

    /**
     * Disable hashing jika diperlukan.
     *
     * @return void
     */
    public static function shouldHashKeys(bool $shouldHashKeys = true)
    {
        self::$shouldHashKeys = $shouldHashKeys;
    }

    /**
     * {@inheritDoc}
     */
    public function run($args)
    {
        $request      = request();
        $key          = $this->resolveRequestSignature($request);
        $maxAttempts  = 150;
        $decaySeconds = 60;

        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            $retryAfter = RateLimiter::availableIn($key);

            event(new TooManyRequests($request, $retryAfter, $maxAttempts, $decaySeconds));

            show_error("Terlalu Banyak Permintaan. Silakan coba lagi dalam {$retryAfter} detik.", 429);
        }

        RateLimiter::hit($key, $decaySeconds);
    }

    /**
     * Resolve request signature
     *
     * @return string
     */
    protected function resolveRequestSignature(Request $request)
    {
        // Jika user login, gunakan user ID
        if ($user = $request->user()) {
            return $this->formatIdentifier($user->getAuthIdentifier());
        }

        // Jika tidak login, gunakan host + IP
        if ($host = $request->getHost()) {
            return $this->formatIdentifier("{$host}|{$request->ip()}");
        }

        // Fallback: cuma IP
        return $this->formatIdentifier($request->ip());
    }

    /**
     * Format identifier dengan hashing
     *
     * @param string $value
     *
     * @return string
     */
    private function formatIdentifier($value)
    {
        return self::$shouldHashKeys ? sha1($value) : $value;
    }
}
