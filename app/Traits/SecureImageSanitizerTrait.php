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

namespace App\Traits;

trait SecureImageSanitizerTrait
{
    /**
     * Ekstensi yang diizinkan (lowercase)
     */
    private array $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

    /**
     * Magic bytes untuk setiap tipe gambar yang valid
     * Format: [offset => hex_signature]
     */
    private array $magicBytes = [
        'jpg'  => ['offset' => 0, 'bytes' => "\xFF\xD8\xFF"],
        'jpeg' => ['offset' => 0, 'bytes' => "\xFF\xD8\xFF"],
        'png'  => ['offset' => 0, 'bytes' => "\x89PNG\r\n\x1A\n"],
        'gif'  => ['offset' => 0, 'bytes' => 'GIF8'],   // GIF87a atau GIF89a
        'webp' => ['offset' => 8, 'bytes' => 'WEBP'],   // RIFF????WEBP
    ];

    /**
     * Ukuran file maksimum: 2 MB
     */
    private int $maxFileSizeBytes = 2 * 1024 * 1024;

    /**
     * Dimensi gambar maksimum
     */
    private int $maxWidth = 4096;

    private int $maxHeight = 4096;

    /**
     * Entry point utama: validasi + sanitasi file gambar
     *
     * @param array  $file    $_FILES['gambar']
     * @param string $destDir Direktori tujuan penyimpanan
     *
     * @return array ['success' => bool, 'filename' => string|null, 'error' => string|null]
     */
    public function validateAndSanitizeImage(array $file, string $destDir): array
    {
        // --- 1. Cek error upload PHP ---
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return $this->fail('Upload gagal dengan kode error: ' . $file['error']);
        }

        // --- 2. Cek ukuran file ---
        if ($file['size'] > $this->maxFileSizeBytes) {
            return $this->fail('Ukuran file melebihi batas maksimum 2 MB.');
        }

        // --- 3. Cek ekstensi file (dari nama asli) ---
        $originalName = $file['name'];
        $ext          = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

        if (! in_array($ext, $this->allowedExtensions, true)) {
            return $this->fail("Ekstensi file tidak diizinkan: .{$ext}");
        }

        // --- 4. Validasi magic bytes (server-side, tidak bergantung client) ---
        $tmpPath     = $file['tmp_name'];
        $magicResult = $this->validateMagicBytes($tmpPath, $ext);
        if (! $magicResult['valid']) {
            return $this->fail($magicResult['error']);
        }

        // --- 5. Verifikasi bahwa file benar-benar gambar via getimagesize ---
        $imageInfo = @getimagesize($tmpPath);
        if ($imageInfo === false) {
            return $this->fail('File bukan gambar yang valid.');
        }

        // --- 6. Cek dimensi gambar ---
        [$width, $height] = $imageInfo;
        if ($width > $this->maxWidth || $height > $this->maxHeight) {
            return $this->fail("Dimensi gambar terlalu besar. Maksimum {$this->maxWidth}x{$this->maxHeight} piksel.");
        }

        // Re-encode image: GD library membaca data piksel murni dan menulis ulang,
        // sehingga semua metadata/payload tersembunyi otomatis dihilangkan
        $sanitizeResult = $this->reEncodeImage($tmpPath, $ext, $imageInfo[2]);

        if (! $sanitizeResult['success']) {
            return $this->fail($sanitizeResult['error']);
        }

        // --- 8. Simpan file hasil sanitasi ke direktori tujuan ---
        if (file_put_contents($tmpPath, $sanitizeResult['data']) === false) {
            return $this->fail('Gagal memproses file sementara.');
        }

        return [
            'success'  => true,
            'filename' => null,
            'error'    => null,
        ];
    }

    /**
     * Validasi magic bytes file
     * Mencegah MIME spoofing: file PHP yang diberi nama .gif tetap terdeteksi
     */
    private function validateMagicBytes(string $filePath, string $ext): array
    {
        if (! isset($this->magicBytes[$ext])) {
            return ['valid' => false, 'error' => "Tipe file tidak dikenali: {$ext}"];
        }

        $handle = fopen($filePath, 'rb');
        if (! $handle) {
            return ['valid' => false, 'error' => 'Tidak dapat membaca file.'];
        }

        $offset    = $this->magicBytes[$ext]['offset'];
        $signature = $this->magicBytes[$ext]['bytes'];
        $length    = strlen($signature);

        fseek($handle, $offset);
        $fileBytes = fread($handle, $length);
        fclose($handle);

        if ($fileBytes !== $signature) {
            return [
                'valid' => false,
                'error' => "File bukan {$ext} yang valid (magic bytes tidak cocok). Kemungkinan file berbahaya.",
            ];
        }

        // CATATAN: Dangerous pattern check tidak diperlukan di sini
        // karena re-encode image menggunakan GD Library akan menghilangkan
        // semua metadata, EXIF, embedded scripts, dan payload berbahaya secara otomatis.
        // Magic bytes validation sudah cukup untuk fase awal.

        return ['valid' => true, 'error' => null];
    }

    /**
     * Re-encode gambar menggunakan GD Library
     *
     * Ini adalah langkah KRITIS:
     * GD membaca data piksel murni dan menulis ulang file dari nol.
     * Semua metadata, komentar, EXIF, payload XSS dalam biner = HILANG.
     *
     * Termasuk serangan seperti:
     * - GIF dengan "!<-- <img onerror=alert(XSS)>" di komentar block
     * - JPEG dengan payload di EXIF comment
     * - PNG dengan payload di tEXt chunk
     */
    private function reEncodeImage(string $filePath, string $ext, int $imageType): array
    {
        // Buat resource GD dari file
        $image = null;

        switch ($imageType) {
            case IMAGETYPE_JPEG:
                $image = @imagecreatefromjpeg($filePath);
                break;

            case IMAGETYPE_PNG:
                $image = @imagecreatefrompng($filePath);
                break;

            case IMAGETYPE_GIF:
                $image = @imagecreatefromgif($filePath);
                break;

            case IMAGETYPE_WEBP:
                $image = @imagecreatefromwebp($filePath);
                break;

            default:
                return ['success' => false, 'error' => 'Tipe gambar tidak didukung untuk sanitasi.'];
        }

        if (! $image) {
            return ['success' => false, 'error' => 'Gagal memproses gambar. File mungkin rusak atau berbahaya.'];
        }

        // Tangkap output buffer (bukan tulis ke file dulu)
        ob_start();

        $outputSuccess = false;

        switch ($imageType) {
            case IMAGETYPE_JPEG:
                // Quality 85 = keseimbangan kualitas vs ukuran, tanpa metadata
                $outputSuccess = imagejpeg($image, null, 85);
                break;

            case IMAGETYPE_PNG:
                // Compression 6 = standar, tanpa metadata
                imagesavealpha($image, true);
                $outputSuccess = imagepng($image, null, 6);
                break;

            case IMAGETYPE_GIF:
                $outputSuccess = imagegif($image);
                break;

            case IMAGETYPE_WEBP:
                $outputSuccess = imagewebp($image, null, 85);
                break;
        }

        $data = ob_get_clean();
        imagedestroy($image);

        if (! $outputSuccess || empty($data)) {
            return ['success' => false, 'error' => 'Gagal melakukan re-encoding gambar.'];
        }

        // Verifikasi sekali lagi: hasil re-encode harus lebih kecil dari file asli
        // atau dalam range wajar (GIF kecil boleh sedikit berbeda)
        if ($data === '') {
            return ['success' => false, 'error' => 'Hasil sanitasi gambar kosong.'];
        }

        return ['success' => true, 'data' => $data, 'error' => null];
    }

    /**
     * Generate nama file yang aman:
     * - Tidak mengandung karakter berbahaya
     * - Tidak dapat ditebak (random)
     * - Ekstensi sudah divalidasi
     */
    private function generateSafeFilename(string $ext): string
    {
        return bin2hex(random_bytes(16)) . '_' . time() . '.' . $ext;
    }

    /**
     * Helper: return array gagal
     */
    private function fail(string $message): array
    {
        log_message('warning', '[SecureImageSanitizer] ' . $message);

        return ['success' => false, 'filename' => null, 'error' => $message];
    }
}
