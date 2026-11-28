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
     * Header yang nilainya berupa daftar direktif CSP sehingga harus digabung per direktif.
     */
    protected const CSP_HEADERS = [
        'content-security-policy',
        'content-security-policy-report-only',
    ];

    /**
     * Direktif CSP yang boleh diperluas tema, dibatasi pada fetch-directive.
     *
     * Direktif di luar daftar ini menentukan batas keamanan dokumen dan navigasi
     * (`frame-ancestors`, `base-uri`, `default-src`, `form-action`, `object-src`,
     * `sandbox`, `report-uri`/`report-to`). Tema hanya berupa data (`security.json`)
     * sehingga tidak boleh dapat melonggarkannya, mis. membuka `frame-ancestors`
     * ke domain lain yang berarti mengembalikan celah clickjacking.
     */
    protected const THEME_MERGEABLE_CSP_DIRECTIVES = [
        'script-src',
        'script-src-elem',
        'style-src',
        'style-src-elem',
        'img-src',
        'font-src',
        'connect-src',
        'media-src',
        'frame-src',
        'child-src',
        'worker-src',
        'manifest-src',
        'prefetch-src',
    ];

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

            // Halaman SSO wajib anti-clickjacking ketat (FR-011): tolak semua framing,
            // lebih ketat dari X-Frame-Options: SAMEORIGIN / frame-ancestors 'self' default.
            if ($request->is('admin/sso-login')) {
                if ($key === 'X-Frame-Options') {
                    $value = 'DENY';
                } elseif ($key === 'Content-Security-Policy') {
                    $value = "default-src 'self'; frame-ancestors 'none';";
                }
            }

            $response->headers->set($key, $value, true);
        }

        return $response;
    }

    /**
     * Load konfigurasi security dari tema dan gabungkan dengan konfigurasi bawaan.
     * - Header baru dari tema akan ditambahkan
     * - Header CSP bawaan digabung per direktif (sumber dari tema ditambahkan)
     * - Header non-CSP bawaan tidak dapat ditimpa tema
     *
     * Nama header dicocokkan tanpa membedakan huruf besar/kecil karena nama header
     * HTTP bersifat case-insensitive; tanpa ini tema bisa menimpa header bawaan
     * hanya dengan menulis nama dalam huruf kecil.
     */
    protected function getMergedSecurityHeaders(): array
    {
        $defaultHeaders = config('security.headers', []) ?? [];
        $themeHeaders   = $this->loadThemeSecurityConfig();

        $defaultKeys = [];

        foreach (array_keys($defaultHeaders) as $defaultKey) {
            $defaultKeys[strtolower((string) $defaultKey)] = $defaultKey;
        }

        foreach ($themeHeaders as $key => $value) {
            $lowerKey = strtolower((string) $key);

            // JSON tidak mengenal komentar, jadi key berawalan garis bawah diperlakukan
            // sebagai catatan pembuat tema dan tidak pernah dikirim sebagai header.
            if (str_starts_with((string) $key, '_')) {
                continue;
            }

            // Config tema tidak tepercaya: tolak nilai yang bukan string/array supaya
            // tidak bergantung pada coercion PHP (null ke string sudah deprecated).
            if (! is_string($value) && ! is_array($value)) {
                Log::warning('Nilai header pada konfigurasi security tema diabaikan karena bukan string atau array', [
                    'header' => $key,
                    'tipe'   => get_debug_type($value),
                ]);

                continue;
            }

            if (! isset($defaultKeys[$lowerKey])) {
                $defaultHeaders[$key]   = $this->normalizeHeaderValue($value);
                $defaultKeys[$lowerKey] = $key;

                continue;
            }

            if (in_array($lowerKey, self::CSP_HEADERS, true)) {
                $defaultKey                  = $defaultKeys[$lowerKey];
                $defaultHeaders[$defaultKey] = $this->mergeCspValue($defaultHeaders[$defaultKey], $value);

                continue;
            }

            Log::warning('Header security dari tema diabaikan karena sudah diatur konfigurasi bawaan', [
                'header' => $key,
            ]);
        }

        return $defaultHeaders;
    }

    /**
     * Gabungkan dua nilai CSP per direktif.
     *
     * Menempel string CSP tema di belakang CSP bawaan akan menghasilkan direktif
     * ganda dalam satu header; sesuai spesifikasi CSP hanya deklarasi pertama yang
     * dipakai browser sehingga sumber dari tema justru diabaikan. Karena itu nilai
     * digabung per direktif: source-list tema ditambahkan ke direktif yang sama.
     *
     * Hanya fetch-directive pada THEME_MERGEABLE_CSP_DIRECTIVES yang boleh diperluas.
     */
    protected function mergeCspValue(string|array $existingValue, string|array $newValue): string
    {
        $merged = $this->parseCspDirectives($existingValue);

        foreach ($this->parseCspDirectives($newValue) as $directive => $sources) {
            if (! in_array($directive, self::THEME_MERGEABLE_CSP_DIRECTIVES, true)) {
                Log::warning('Direktif CSP dari tema diabaikan karena bukan fetch-directive yang boleh diperluas', [
                    'direktif' => $directive,
                ]);

                continue;
            }

            $sources = $this->withoutNoneKeyword($sources);

            if ($sources === []) {
                continue;
            }

            // "'none'" tidak valid bila digabung token lain: browser menolak direktifnya
            // lalu jatuh ke default-src, sehingga kebijakan efektif berubah tanpa terlihat.
            $existingSources = $this->withoutNoneKeyword($merged[$directive] ?? []);

            $merged[$directive] = array_values(array_unique(array_merge($existingSources, $sources)));
        }

        $parts = [];

        foreach ($merged as $directive => $sources) {
            $parts[] = $sources === [] ? $directive . ';' : $directive . ' ' . implode(' ', $sources) . ';';
        }

        return implode(' ', $parts);
    }

    /**
     * Buang keyword 'none' dari daftar sumber.
     *
     * @param list<string> $sources
     *
     * @return list<string>
     */
    protected function withoutNoneKeyword(array $sources): array
    {
        return array_values(array_filter($sources, static fn (string $source): bool => strtolower($source) !== "'none'"));
    }

    /**
     * Pecah nilai header CSP menjadi map direktif => daftar sumber.
     *
     * @return array<string, list<string>>
     */
    protected function parseCspDirectives(string|array $value): array
    {
        $directives = [];

        foreach (explode(';', $this->normalizeHeaderValue($value)) as $chunk) {
            $tokens = preg_split('/\s+/', trim($chunk), -1, PREG_SPLIT_NO_EMPTY) ?: [];

            if ($tokens === []) {
                continue;
            }

            $directive = strtolower((string) array_shift($tokens));

            $directives[$directive] = array_values(array_unique(array_merge($directives[$directive] ?? [], $tokens)));
        }

        return $directives;
    }

    /**
     * Pastikan value header satu baris dan aman untuk Response header Symfony/Laravel.
     */
    protected function normalizeHeaderValue(string|array $value): string
    {
        if (is_array($value)) {
            $value = implode(' ', array_map('strval', array_filter($value, 'is_scalar')));
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
