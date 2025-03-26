<?php

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Storage;
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
     * @return \Symfony\Component\HttpFoundation\StreamedResponse Respons streaming file dari storage.
     * 
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException Jika file utama maupun file default tidak ditemukan, atau akses tidak valid.
     * @throws \League\Flysystem\PathTraversalDetected Jika terjadi eksploitasi path traversal.
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
                function ($response) use ($headers) {
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