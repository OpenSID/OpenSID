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
use League\Flysystem\PathTraversalDetected;
use Symfony\Component\HttpFoundation\Response;

class ServeFileController extends Controller
{
    /**
     * Disk statis yang diizinkan sebagai sumber file fallback.
     * Disk 'desa' sengaja tidak disertakan karena berisi data sensitif.
     */
    private const ALLOWED_DISKS = ['assets', 'public'];

    /**
     * Pemetaan prefix path → disk.
     *
     * Perluasan akses cukup dengan menambah pasangan prefix/folder baru di sini.
     */
    private const DISK_BY_PREFIX = [
        'upload/'     => 'desa',
        'logo/'       => 'desa',
        'pengaturan/' => 'desa',
        'anjungan/'   => 'desa',
        'impor/'      => 'template',
        'arsip/'      => 'desa',
    ];

    /**
     * Prefix publik desa yang selaras dengan `config/filesystems.php:153` `links`.
     * Dipakai fallback `desa/{path}` saat symlink `public/desa/*` tidak tersedia.
     */
    private const PUBLIC_DESA_PREFIXES = ['upload/', 'logo/', 'pengaturan/', 'anjungan/'];

    /**
     * Ekstensi file yang diizinkan sebagai fallback default dan aset publik desa.
     * Mencakup gambar, font, dokumen publik — blokir .php/.env/.htaccess.
     */
    private const ALLOWED_ASSET_EXTENSIONS = [
        'css', 'js', 'map', 'svg', 'png', 'jpg', 'jpeg', 'gif', 'webp', 'ico',
        'woff', 'woff2', 'ttf', 'eot', 'json', 'webmanifest', 'txt', 'xml',
        'pdf', 'doc', 'docx', 'xls', 'xlsx', 'xlsm', 'ppt', 'pptx', 'zip', 'rar',
    ];

    /**
     * Ekstensi file yang diizinkan di area template impor (`impor/`).
     */
    private const ALLOWED_TEMPLATE_EXTENSIONS = ['json', 'xls', 'xlsx', 'xlsm'];

    /**
     * Ekstensi file yang diizinkan di area arsip (`arsip/`).
     */
    private const ALLOWED_ARSIP_EXTENSIONS = ['pdf'];

    /**
     * Sajikan file storage secara streaming.
     *
     * Route dilindungi middleware `signed`. Jika file utama tidak ditemukan di
     * disk terpilih, diambil fallback dari `?default=` pada disk `?defaultDisk=`
     * yang dibatasi pada disk statis.
     */
    public function index(Request $request): Response
    {
        $path = (string) $request->query('path', '');

        try {
            [$diskName, $path] = $this->resolveWithFallback($request, $path);

            return $this->fileResponse($path, $diskName, 'no-store, no-cache, must-revalidate, max-age=0');
        } catch (PathTraversalDetected $e) {
            logger()->error($e);
            abort(404);
        }
    }

    /**
     * Fallback publik untuk `desa/{path}` saat symlink `public/desa/*`
     * tidak tersedia (shared hosting tanpa FollowSymLinks). Hanya folder di
     * `PUBLIC_DESA_PREFIXES` yang dilayani, tanpa `signed` — web server
     * tetap fast-path bila symlink ada (`.htaccess` `!-f`).
     */
    public function fallback(Request $request, string $path): Response
    {
        // Laravel sudah urldecode `path`; normalisasi `..` tetap ditolak.
        try {
            $diskName = $this->resolveDiskName($path);

            if ($diskName === null || ! in_array(explode('/', $path)[0] . '/', self::PUBLIC_DESA_PREFIXES, true)) {
                abort(404);
            }

            return $this->fileResponse($path, $diskName, $this->cacheControlFor($path, true));
        } catch (PathTraversalDetected $e) {
            logger()->error($e);
            abort(404);
        }
    }

    private function resolveWithFallback(Request $request, string $path): array
    {
        $diskName = $this->resolveDiskName($path);

        if ($diskName === null) {
            abort(404);
        }

        $disk = Storage::disk($diskName);

        if ($disk->fileExists($path)) {
            return [$diskName, $path];
        }

        $defaultPath = preg_replace('/\?.*$/', '', (string) $request->query('default'));
        $defaultDisk = $request->query('defaultDisk', 'assets');

        if (! in_array($defaultDisk, self::ALLOWED_DISKS, true) || ! $this->isAllowedAsset($defaultPath)) {
            abort(404);
        }

        $disk = Storage::disk($defaultDisk);

        if (! $disk->fileExists($defaultPath)) {
            abort(404);
        }

        return [$defaultDisk, $defaultPath];
    }

    private function fileResponse(string $path, string $diskName, string $cacheControl): Response
    {
        $headers = [
            'Cache-Control'           => $cacheControl,
            'Content-Security-Policy' => "default-src 'none'; style-src 'unsafe-inline'; sandbox",
        ];

        try {
            return tap(
                Storage::disk($diskName)->response(path: $path, headers: $headers),
                static function ($response) use ($headers) {
                    if (! $response->headers->has('Content-Security-Policy')) {
                        $response->headers->replace($headers);
                    }
                }
            );
        } catch (\League\Flysystem\UnableToRetrieveMetadata $e) {
            logger()->warning('File tidak ditemukan saat response: ' . $path, ['exception' => $e->getMessage()]);
            abort(404);
        }
    }

    /**
     * Resolusi disk utama dari prefix path pertama yang cocok; `null` bila
     * tidak ada prefix yang diizinkan atau path tidak lolos aturan area.
     *
     * Segmen `..` ditolak eksplisit karena normalizer Flysystem mengizinkan
     * relative path: `upload/../app_key` dicollapse menjadi `app_key` tanpa
     * memicu PathTraversalDetected.
     */
    private function resolveDiskName(string $path): ?string
    {
        if (str_contains($path, '..')) {
            return null;
        }

        foreach (self::DISK_BY_PREFIX as $prefix => $diskName) {
            if (! str_starts_with($path, $prefix)) {
                continue;
            }

            if ($prefix === 'impor/' && ! $this->hasAllowedTemplateExtension($path)) {
                return null;
            }

            if ($prefix === 'arsip/' && ! $this->hasAllowedArsipExtension($path)) {
                return null;
            }

            // Folder publik desa hanya melayani ekstensi aset yang diizinkan
            // agar .php/.env/.json tidak terekspos bila symlink gagal.
            if (in_array($prefix, self::PUBLIC_DESA_PREFIXES, true) && ! $this->isAllowedAsset($path)) {
                return null;
            }

            return $diskName;
        }

        return null;
    }

    private function cacheControlFor(string $path, bool $forcePublic = false): string
    {
        // Aset publik desa di-cache agresif (first-hit PHP, selanjutnya 304/ETag
        // dari Storage::response); privat tetap no-store.
        $isPublicDesa = $forcePublic;

        if (! $isPublicDesa) {
            foreach (self::PUBLIC_DESA_PREFIXES as $prefix) {
                if (str_starts_with($path, $prefix) && $this->isAllowedAsset($path)) {
                    $isPublicDesa = true;
                    break;
                }
            }
        }

        return $isPublicDesa
            ? 'public, max-age=31536000, immutable'
            : 'no-store, no-cache, must-revalidate, max-age=0';
    }

    /**
     * Area template impor hanya melayani file dengan ekstensi yang
     * diizinkan sehingga skrip (PHP) dan file tanpa ekstensi tidak pernah
     * terlayani.
     */
    private function hasAllowedTemplateExtension(string $path): bool
    {
        $extension = strtolower((string) pathinfo($path, PATHINFO_EXTENSION));

        return $extension !== '' && in_array($extension, self::ALLOWED_TEMPLATE_EXTENSIONS, true);
    }

    /**
     * Area arsip hanya melayani file PDF untuk verifikasi surat
     * sehingga file types lain tidak pernah terlayani.
     */
    private function hasAllowedArsipExtension(string $path): bool
    {
        $extension = strtolower((string) pathinfo($path, PATHINFO_EXTENSION));

        return $extension !== '' && in_array($extension, self::ALLOWED_ARSIP_EXTENSIONS, true);
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
}
