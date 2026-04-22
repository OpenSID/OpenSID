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

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\FilesystemException;
use League\Flysystem\PathTraversalDetected;

class Asset extends CI_Controller
{
    private const ALLOWED_DISKS = ['assets', 'desa', 'public'];

    private const SECURITY_HEADERS = [
        'Content-Security-Policy' => "default-src 'none'; style-src 'unsafe-inline'; sandbox",
    ];

    // CSS/JS tanpa content hash — bisa berubah saat update tema.
    // Parameter ?v= di URL sudah menjadi cache buster alami,
    // sehingga max-age bisa cukup panjang.
    private const CACHE_REVALIDATE = [
        'Cache-Control' => 'public, max-age=604800, must-revalidate',
    ];

    // Font, gambar, dan CSS/JS dengan content hash di nama file.
    // Tidak pernah berubah untuk URL yang sama.
    private const CACHE_IMMUTABLE = [
        'Cache-Control' => 'public, max-age=31536000, immutable',
    ];

    public function __construct()
    {
        // Matikan session_cache_limiter sebelum parent::__construct()
        // agar CI3 tidak inject Expires, Pragma, dan Cache-Control: no-store
        // yang akan konflik dengan cache header milik controller ini.
        session_cache_limiter('');

        parent::__construct();

        // Hapus header Set-Cookie yang mungkin sudah di-set oleh session CI3
        header_remove('Set-Cookie');

        $this->load->helper('theme');

        theme_active();
    }

    public function serveTheme()
    {
        $diskRoot = base_path(theme_full_path() . '/assets');

        return $this->serveAsset($diskRoot);
    }

    public function serveModule($moduleName)
    {
        $moduleName = $this->getOriginalModule($moduleName);
        $diskRoot   = base_path("Modules/{$moduleName}/Views/assets");

        return $this->serveAsset($diskRoot);
    }

    private function serveAsset($rootPath)
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
            $etag         = "\"$checksum\"";
            $lastModified = $disk->lastModified($finalPath);

            // Cek If-None-Match (ETag revalidation)
            if ($request->headers->get('If-None-Match') === $etag) {
                return response('', 304)->send();
            }

            // Cek If-Modified-Since
            $ifModifiedSince = $request->headers->get('If-Modified-Since');
            if ($ifModifiedSince && $lastModified <= strtotime($ifModifiedSince)) {
                return response('', 304)->send();
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
            )->send();
        } catch (PathTraversalDetected $e) {
            logger()->error($e);
            show_404();
        } catch (FilesystemException $e) {
            logger()->error($e);
            show_404();
        }
    }

    /**
     * Tentukan cache headers berdasarkan tipe dan nama file.
     *
     * ETag dan Last-Modified sudah di-handle otomatis oleh $disk->response(),
     * sehingga browser tetap bisa revalidasi via 304 Not Modified meski
     * max-age belum habis — misalnya saat tema diupdate.
     *
     * Parameter ?v= dan ?themeVersion= di URL juga sudah menjadi cache buster
     * alami: kalau versi berubah, URL berubah, browser otomatis fetch ulang.
     */
    private function resolveHeaders(string $path): array
    {
        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        // Font dan gambar tidak pernah berubah isi-nya untuk path yang sama
        $immutableExtensions = ['woff', 'woff2', 'ttf', 'eot', 'png', 'jpg', 'jpeg', 'gif', 'webp', 'ico', 'svg'];

        if (in_array($ext, $immutableExtensions, true)) {
            return self::CACHE_IMMUTABLE + self::SECURITY_HEADERS;
        }

        // CSS/JS dengan content hash di nama file: app.a3f9c1.css, chunk.8b2d44.js
        if (preg_match('/\.[a-f0-9]{6,}\.(css|js)$/i', $path)) {
            return self::CACHE_IMMUTABLE + self::SECURITY_HEADERS;
        }

        // CSS/JS biasa — browser revalidasi via ETag/Last-Modified setelah 7 hari
        return self::CACHE_REVALIDATE + self::SECURITY_HEADERS;
    }

    private function resolveDiskAndPath($primaryDisk, $path, Request $request)
    {
        if (! empty($path) && $path !== '.' && $primaryDisk->exists($path)) {
            return [$primaryDisk, $path];
        }

        $defaultPath = $request->query('default');
        $diskName    = $request->query('defaultDisk', 'desa');

        // Validasi defaultPath lebih awal, sebelum operasi lain
        if (empty($defaultPath) || $defaultPath === '.') {
            show_404();
        }

        // Validasi diskName
        if (! in_array($diskName, self::ALLOWED_DISKS)) {
            show_404();
        }

        $disk = Storage::disk($diskName);

        if (! $disk->exists($defaultPath)) {
            show_404();
        }

        return [$disk, $defaultPath];
    }

    /**
     * Membersihkan path file dari karakter yang tidak diinginkan
     *
     * @param string $filePath
     *
     * @return string
     */
    private function cleanFilePath($filePath)
    {
        // Hapus karakter ? dan parameter query yang mungkin ada di akhir
        $cleanPath = preg_replace('/\?.*$/', '', $filePath);

        // Hapus slash (/) di awal path
        $cleanPath = ltrim($cleanPath, '/');

        // Hapus trailing whitespace atau karakter ? yang tersisa
        return rtrim($cleanPath, " \t\n\r\0\x0B?");
    }

    private function getOriginalModule($moduleName)
    {
        return match ($moduleName) {
            'bukutamu' => 'BukuTamu',
            'ppid'     => 'PPID',
            default    => ucfirst($moduleName),
        };
    }
}
