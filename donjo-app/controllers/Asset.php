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
use League\Flysystem\PathTraversalDetected;

class Asset extends CI_Controller
{
    private const ALLOWED_DISKS    = ['assets', 'desa', 'public'];
    private const SECURITY_HEADERS = [
        'Cache-Control'           => 'no-store, no-cache, must-revalidate, max-age=0',
        'Content-Security-Policy' => "default-src 'none'; style-src 'unsafe-inline'; sandbox",
    ];

    public function __construct()
    {
        parent::__construct();

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

            [$disk, $finalPath] = $this->resolveDiskAndPath($primaryDisk, $path, $request);

            return tap(
                $disk->response(path: $finalPath, headers: self::SECURITY_HEADERS),
                static function ($response) {
                    if (! $response->headers->has('Content-Security-Policy')) {
                        $response->headers->replace(self::SECURITY_HEADERS);
                    }
                }
            )->send();
        } catch (PathTraversalDetected $e) {
            logger()->error($e);
            show_404();
        }
    }

    private function resolveDiskAndPath($primaryDisk, $path, Request $request)
    {
        // Gunakan file utama jika ada
        if ($primaryDisk->exists($path)) {
            return [$primaryDisk, $path];
        }

        // Fallback ke file default
        $defaultPath = $request->query('default');
        $diskName    = $request->query('defaultDisk', 'desa');

        if (! in_array($diskName, self::ALLOWED_DISKS) || ! $defaultPath) {
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
        $originalModule = ucfirst($moduleName);

        switch($moduleName) {
            case 'bukutamu':
                $originalModule = 'BukuTamu';
                break;

            case 'ppid':
                $originalModule = 'PPID';
                break;
        }

        return $originalModule;
    }
}
