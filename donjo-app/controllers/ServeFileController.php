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

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use League\Flysystem\PathTraversalDetected;

class ServeFileController extends CI_Controller
{
    /**
     * Mengambil file dari storage dan mengembalikannya sebagai respons streaming.
     *
     * - Jika file utama tidak ditemukan di disk `desa`, sistem akan mencoba mengambil file default dari disk yang ditentukan dalam parameter `?default=`.
     * - Parameter `?defaultDisk=` hanya berlaku untuk file default dan harus salah satu dari `assets`, `desa`, atau `public`.
     * - Memvalidasi tanda tangan URL menggunakan `hasValidSignature()`.
     * - Menyertakan header tambahan untuk pengaturan cache dan kebijakan keamanan.
     *
     * @throws PathTraversalDetected                                        Jika terjadi eksploitasi path traversal.
     * @throws Symfony\Component\HttpKernel\Exception\NotFoundHttpException Jika file utama maupun file default tidak ditemukan, atau akses tidak valid.
     *
     * @return Symfony\Component\HttpFoundation\StreamedResponse Respons streaming file dari storage.
     *
     * @queryParam string $path Path file utama dalam storage. Wajib diisi.
     * @queryParam string $default Path file default yang akan digunakan jika file utama tidak ditemukan. Opsional.
     * @queryParam string $defaultDisk Disk penyimpanan untuk file default (`assets`, `desa`, atau `public`). Default: `desa`.
     *
     * @example
     * Contoh penggunaan untuk menghasilkan signed URL:
     *
     * ```php
     * use Illuminate\Support\Facades\URL;
     *
     * $url = URL::signedRoute('storage.desa', [
     *     'path'        => 'upload/file.jpg',
     *     'default'     => 'upload/default.jpg',
     *     'defaultDisk' => 'public',
     * ]);
     *
     * echo $url;
     *  // Contoh hasil: http://example.com/storage-desa?path=upload/file.jpg&default=upload/default.jpg&defaultDisk=public&signature=abcdef123456
     * ```
     */
    public function index()
    {
        $request = request();
        $path    = $request->query('path', '');

        // Periksa apakah URL memiliki tanda tangan yang valid
        if (! $this->hasValidSignature($request)) {
            show_404();
        }

        try {
            $primaryDisk = Storage::disk('desa');
            $disk        = $primaryDisk;

            // Jika file utama tidak ada, gunakan file default dari query parameter
            if (! $primaryDisk->fileExists($path)) {
                $defaultPath = $request->query('default');
                $diskName    = $request->query('defaultDisk', 'desa');

                // Hanya izinkan disk tertentu untuk file default
                $allowedDisks = ['assets', 'desa', 'public'];
                if (! in_array($diskName, $allowedDisks) || ! $defaultPath) {
                    show_404();
                }

                $disk = Storage::disk($diskName);

                if (! $disk->fileExists($defaultPath)) {
                    show_404();
                }

                $path = $defaultPath;
            }

            // Header tambahan untuk keamanan
            $headers = [
                'Cache-Control'           => 'no-store, no-cache, must-revalidate, max-age=0',
                'Content-Security-Policy' => "default-src 'none'; style-src 'unsafe-inline'; sandbox",
            ];

            return tap(
                $disk->response(path: $path, headers: $headers),
                static function ($response) use ($headers) {
                    if (! $response->headers->has('Content-Security-Policy')) {
                        $response->headers->replace($headers);
                    }
                }
            )->send();
        } catch (PathTraversalDetected $e) {
            logger()->error($e);
            show_404();
        }
    }

    /**
     * Determine if the request has a valid signature if applicable.
     */
    protected function hasValidSignature(Request $request): bool
    {
        return URL::hasValidSignature($request);
    }
}
