<?php

declare(strict_types=1);

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

namespace App\Services\Acak\Support;

use App\Services\Acak\AcakResult;

/**
 * Terapkan mode foto konten ('watermark' | 'picsum') ke satu berkas gambar,
 * lengkap dengan varian thumbnail kecil_/sedang_ bila relevan. Semua operasi
 * degradasi aman dan mencatat URL yang diproses ke {@see AcakResult}.
 */
final class ContentImageProcessor
{
    public function __construct(
        private readonly ImageToolkit $toolkit,
        private readonly string $mode,
    ) {
    }

    /**
     * Proses gambar konten (artikel/galeri) beserta thumbnail kecil_/sedang_.
     * Berkas utama sering absen (hanya thumbnail tersimpan) — apa pun yang ada
     * akan diproses. Mengembalikan true bila minimal satu berkas berubah.
     */
    public function processContentImage(string $dir, string $filename, AcakResult $result): bool
    {
        $dir      = rtrim($dir, '/\\') . DIRECTORY_SEPARATOR;
        $mainPath = $dir . $filename;
        $hmac     = $this->toolkit->hmac('content_foto', $filename);

        if ($this->mode === 'picsum') {
            $ok = $this->replaceWithPicsum($mainPath, $dir, $filename, $hmac)
                || $this->placeholderSet($mainPath, $dir, $filename, $hmac);

            if ($ok) {
                $result->trackUrl($mainPath);
                foreach (['kecil_', 'sedang_'] as $prefix) {
                    $result->trackUrl($dir . $prefix . $filename);
                }
            }

            return $ok;
        }

        // Mode watermark: berkas utama (bila ada) + varian yang ada di disk.
        $ok = false;

        if (is_file($mainPath) && $this->toolkit->watermarkFile($mainPath)) {
            $result->trackUrl($mainPath);
            $ok = true;
        }

        foreach (['kecil_', 'sedang_'] as $prefix) {
            $variant = $dir . $prefix . $filename;
            if (is_file($variant) && $this->toolkit->watermarkFile($variant)) {
                $result->trackUrl($variant);
                $ok = true;
            }
        }

        return $ok;
    }

    /**
     * Proses satu gambar media (embed TinyMCE, ukuran penuh, tanpa thumbnail).
     */
    public function processMediaImage(string $path, AcakResult $result): bool
    {
        if ($this->mode === 'watermark') {
            $ok = $this->toolkit->watermarkFile($path);
            if ($ok) {
                $result->trackUrl($path);
            }

            return $ok;
        }

        // picsum
        $ext  = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        $hmac = $this->toolkit->hmac('media_foto', basename($path));
        $data = $this->toolkit->fetchRemote($this->picsumUrl($hmac), 1024);

        if ($data !== null) {
            $src = @imagecreatefromstring($data);
            if ($src !== false) {
                $ok = $this->toolkit->saveImageAs($src, $path, $ext);
                imagedestroy($src);
                if ($ok) {
                    $result->trackUrl($path);

                    return true;
                }
            }
        }

        $ok = $this->toolkit->placeholder($path, 800, 534, $hmac);
        if ($ok) {
            $result->trackUrl($path);
        }

        return $ok;
    }

    /**
     * Ambil foto lanskap deterministik dari Picsum lalu tulis ke berkas utama +
     * thumbnail kecil_ (300×200) dan sedang_ (480×320, hanya bila sudah ada).
     */
    private function replaceWithPicsum(string $mainPath, string $dir, string $filename, string $hmac): bool
    {
        $data = $this->toolkit->fetchRemote($this->picsumUrl($hmac), 1024);
        if ($data === null) {
            return false;
        }

        $src = @imagecreatefromstring($data);
        if ($src === false) {
            return false;
        }

        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION)) ?: 'jpg';
        $this->toolkit->saveImageAs($src, $mainPath, $ext);

        foreach ([['kecil_', 300, 200], ['sedang_', 480, 320]] as [$prefix, $tw, $th]) {
            $thumbPath = $dir . $prefix . $filename;
            if ($prefix === 'kecil_' || is_file($thumbPath)) {
                $thumb = imagecreatetruecolor($tw, $th);
                $white = imagecolorallocate($thumb, 255, 255, 255);
                imagefilledrectangle($thumb, 0, 0, $tw - 1, $th - 1, $white);
                imagecopyresampled($thumb, $src, 0, 0, 0, 0, $tw, $th, 800, 534);
                $this->toolkit->saveImageAs($thumb, $thumbPath, $ext);
                imagedestroy($thumb);
            }
        }

        imagedestroy($src);

        return true;
    }

    /**
     * Cadangan GD saat Picsum tak terjangkau: placeholder "FOTO DEMO" untuk berkas
     * utama + kecil_ (selalu) dan sedang_ (hanya bila sudah ada).
     */
    private function placeholderSet(string $mainPath, string $dir, string $filename, string $hmac): bool
    {
        $any = false;
        foreach ([[$mainPath, 800, 534], [$dir . 'kecil_' . $filename, 300, 200], [$dir . 'sedang_' . $filename, 480, 320]] as [$path, $w, $h]) {
            if (str_starts_with(basename($path), 'sedang_') && ! is_file($path)) {
                continue;
            }
            if ($this->toolkit->placeholder($path, $w, $h, $hmac)) {
                $any = true;
            }
        }

        return $any;
    }

    private function picsumUrl(string $hmac): string
    {
        return 'https://picsum.photos/seed/' . substr($hmac, 0, 12) . '/800/534';
    }
}
