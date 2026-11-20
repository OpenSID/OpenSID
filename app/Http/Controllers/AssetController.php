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

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\FilesystemException;
use League\Flysystem\PathTraversalDetected;
use Symfony\Component\HttpFoundation\Response;

class AssetController extends Controller
{
    /**
     * Disk statis yang diizinkan sebagai sumber file fallback.
     * Disk 'desa' sengaja tidak disertakan karena berisi data sensitif.
     */
    private const ALLOWED_DISKS = ['assets', 'public'];

    /**
     * Fallback dibatasi pada ekstensi file aset statis; file sensitif
     * seperti app_key, config/*, .env, dan *.php otomatis tertolak.
     */
    private const ALLOWED_ASSET_EXTENSIONS = [
        'css', 'js', 'map', 'svg', 'png', 'jpg', 'jpeg', 'gif', 'webp', 'ico',
        'woff', 'woff2', 'ttf', 'eot', 'json', 'webmanifest', 'txt', 'xml',
    ];

    private const SECURITY_HEADERS = [
        'Content-Security-Policy' => "default-src 'none'; style-src 'unsafe-inline'; sandbox",
    ];

    /**
     * CSS/JS tanpa content hash dapat berubah saat tema diupdate; parameter
     * ?v= di URL sudah berperan sebagai cache buster.
     */
    private const CACHE_REVALIDATE = [
        'Cache-Control' => 'public, max-age=604800, must-revalidate',
    ];

    /**
     * Font, gambar, dan CSS/JS dengan content hash di nama file tidak
     * berubah untuk URL yang sama.
     */
    private const CACHE_IMMUTABLE = [
        'Cache-Control' => 'public, max-age=31536000, immutable',
    ];

    public function serveTheme(): Response
    {
        theme_active();

        $diskRoot = base_path(theme_full_path() . '/assets');

        return $this->serveAsset($diskRoot);
    }

    public function serveModule(string $moduleName): Response
    {
        $moduleName = $this->getOriginalModule($moduleName);
        $diskRoot   = base_path("Modules/{$moduleName}/Views/assets");

        return $this->serveAsset($diskRoot);
    }

    private function serveAsset(string $rootPath): Response
    {
        $request = request();
        $path    = $this->cleanFilePath($request->query('file', ''));

        try {
            $primaryDisk = Storage::build([
                'driver' => 'local',
                'root'   => $rootPath,
                'links'  => false,
            ]);

            /**
             * @var \Illuminate\Filesystem\FilesystemAdapter $disk
             * @var string                                   $finalPath
             */
            [$disk, $finalPath] = $this->resolveDiskAndPath($primaryDisk, $path, $request);

            $checksum     = $disk->checksum($finalPath);
            $etag         = "\"{$checksum}\"";
            $lastModified = $disk->lastModified($finalPath);

            if ($request->headers->get('If-None-Match') === $etag) {
                return response('', 304);
            }

            $ifModifiedSince = $request->headers->get('If-Modified-Since');
            if ($ifModifiedSince && $lastModified <= strtotime($ifModifiedSince)) {
                return response('', 304);
            }

            $extraHeaders = [
                'ETag'          => $etag,
                'Last-Modified' => gmdate('D, d M Y H:i:s', $lastModified) . ' GMT',
            ];

            return tap(
                $disk->response(path: $finalPath, headers: $this->resolveHeaders($finalPath) + $extraHeaders),
                static function ($response) {
                    $response->headers->remove('Pragma');
                    $response->headers->remove('Expires');
                    $response->headers->remove('Set-Cookie');

                    if (! $response->headers->has('Content-Security-Policy')) {
                        $response->headers->set(
                            'Content-Security-Policy',
                            self::SECURITY_HEADERS['Content-Security-Policy']
                        );
                    }
                }
            );
        } catch (PathTraversalDetected $e) {
            logger()->error($e);
            abort(404);
        } catch (FilesystemException $e) {
            logger()->error($e);
            abort(404);
        }
    }

    /**
     * Cache headers berdasarkan jenis aset.
     *
     * ETag dan Last-Modified ditangani otomatis oleh $disk->response(),
     * sehingga revalidasi via 304 tetap berjalan meski max-age belum habis,
     * misalnya saat tema diupdate. Parameter ?v= pada URL sekaligus menjadi
     * cache buster.
     */
    private function resolveHeaders(string $path): array
    {
        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        $immutableExtensions = ['woff', 'woff2', 'ttf', 'eot', 'png', 'jpg', 'jpeg', 'gif', 'webp', 'ico', 'svg'];

        if (in_array($ext, $immutableExtensions, true)) {
            return self::CACHE_IMMUTABLE + self::SECURITY_HEADERS;
        }

        // CSS/JS dengan content hash di nama file: app.a3f9c1.css, chunk.8b2d44.js
        if (preg_match('/\.[a-f0-9]{6,}\.(css|js)$/i', $path)) {
            return self::CACHE_IMMUTABLE + self::SECURITY_HEADERS;
        }

        return self::CACHE_REVALIDATE + self::SECURITY_HEADERS;
    }

    private function resolveDiskAndPath($primaryDisk, string $path, Request $request): array
    {
        if (! empty($path) && $path !== '.' && $primaryDisk->exists($path)) {
            return [$primaryDisk, $path];
        }

        $defaultPath = $this->cleanFilePath($request->query('default'));
        $diskName    = $request->query('defaultDisk', 'assets');

        if (! $this->isAllowedAsset($defaultPath) || ! in_array($diskName, self::ALLOWED_DISKS, true)) {
            abort(404);
        }

        $disk = Storage::disk($diskName);

        if (! $disk->exists($defaultPath)) {
            abort(404);
        }

        return [$disk, $defaultPath];
    }

    /**
     * Fallback hanya diperbolehkan untuk file aset statis, sehingga file
     * tanpa ekstensi (app_key), file PHP (config/*), dan file dot (.env)
     * tidak pernah terlayani.
     */
    private function isAllowedAsset(string $path): bool
    {
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        return $extension !== '' && in_array($extension, self::ALLOWED_ASSET_EXTENSIONS, true);
    }

    /**
     * Bersihkan path dari parameter query, slash di awal, dan karakter sisa.
     */
    private function cleanFilePath($filePath): string
    {
        $cleanPath = preg_replace('/\?.*$/', '', (string) $filePath);
        $cleanPath = ltrim($cleanPath, '/');

        return rtrim($cleanPath, " \t\n\r\0\x0B?");
    }

    private function getOriginalModule(string $moduleName): string
    {
        return match ($moduleName) {
            'bukutamu' => 'BukuTamu',
            'ppid'     => 'PPID',
            default    => ucfirst($moduleName),
        };
    }
}
