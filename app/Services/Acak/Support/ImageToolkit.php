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

use GdImage;

/**
 * Primitif pengolahan gambar berbasis GD untuk proses acak: watermark, placeholder
 * berwarna, konversi HSL→RGB, penyimpanan sesuai ekstensi, serta pengambilan gambar
 * jarak jauh (Picsum/DiceBear) yang deterministik lewat HMAC berbasis seed.
 *
 * Semua operasi men-degrade dengan aman: bila ekstensi GD/cURL tidak tersedia atau
 * berkas gagal dibaca, metode mengembalikan false alih-alih melempar. Dipakai
 * bersama oleh sanitizer foto warga/pamong dan foto konten.
 */
class ImageToolkit
{
    public const FONT_PATH = 'desa/upload/fonts/times_new_romanbd.ttf';

    /** Font cadangan bila FONT_PATH hilang — bundel aplikasi, bukan aset vendor CI3. */
    private const FALLBACK_FONT_PATH = 'desa/upload/fonts/times_new_roman.ttf';

    public function __construct(private readonly string $seed = 'opensid-demo')
    {
    }

    /**
     * HMAC-SHA256 heksadesimal deterministik untuk (domain, key) berbasis seed.
     */
    public function hmac(string $domain, string $key): string
    {
        return hash_hmac('sha256', $domain . '|' . $key, $this->seed);
    }

    public function gdAvailable(): bool
    {
        return extension_loaded('gd');
    }

    public function curlAvailable(): bool
    {
        return extension_loaded('curl');
    }

    /**
     * Path absolut font TTF; jatuh ke font cadangan bawaan aplikasi bila font utama hilang.
     */
    public function fontPath(): ?string
    {
        $primary = base_path(self::FONT_PATH);
        if (is_file($primary) && filesize($primary) > 0) {
            return $primary;
        }

        $fallback = base_path(self::FALLBACK_FONT_PATH);

        return (is_file($fallback) && filesize($fallback) > 0) ? $fallback : null;
    }

    /**
     * Ambil gambar mentah dari URL (mis. Picsum/DiceBear). Mengembalikan data biner
     * atau null bila cURL tak tersedia/gagal/isi terlalu kecil.
     */
    public function fetchRemote(string $url, int $minBytes = 1024): ?string
    {
        if (! $this->curlAvailable()) {
            return null;
        }

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 8,
            CURLOPT_CONNECTTIMEOUT => 4,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_USERAGENT      => 'OpenSID/AcakService',
        ]);
        $data = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if (! is_string($data) || $code !== 200 || strlen($data) < $minBytes) {
            return null;
        }

        return $data;
    }

    /**
     * Simpan resource GD ke disk sesuai ekstensi. Selalu mengembalikan bool sukses.
     */
    public function saveImageAs(GdImage $img, string $path, string $ext): bool
    {
        return match (strtolower($ext)) {
            'png'   => (bool) imagepng($img, $path),
            'gif'   => (bool) imagegif($img, $path),
            'webp'  => function_exists('imagewebp') ? (bool) imagewebp($img, $path, 88) : (bool) imagejpeg($img, $path, 88),
            default => (bool) imagejpeg($img, $path, 88),
        };
    }

    /**
     * Baca berkas gambar menjadi resource GD sesuai ekstensi; false bila gagal.
     */
    public function readImage(string $path, string $ext): GdImage|false
    {
        return match (strtolower($ext)) {
            'jpg', 'jpeg' => @imagecreatefromjpeg($path),
            'png'         => @imagecreatefrompng($path),
            'gif'         => @imagecreatefromgif($path),
            'webp'        => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($path) : false,
            default       => false,
        };
    }

    /**
     * Tempel watermark diagonal "FOTO DEMO" semi-transparan pada berkas gambar
     * secara in-place. Mengembalikan false bila GD tak ada / berkas tak terbaca.
     */
    public function watermarkFile(string $path): bool
    {
        if (! $this->gdAvailable() || ! is_file($path)) {
            return false;
        }

        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        $src = $this->readImage($path, $ext);
        if ($src === false) {
            return false;
        }

        // Gambar palet (PNG 8-bit/GIF) bisa membuat imagecolorallocatealpha()
        // mengembalikan false saat palet penuh; naikkan ke truecolor agar alokasi
        // warna & alpha blending selalu berhasil.
        if (! imageistruecolor($src) && function_exists('imagepalettetotruecolor')) {
            imagepalettetotruecolor($src);
        }

        $w    = imagesx($src);
        $h    = imagesy($src);
        $font = $this->fontPath();

        imagealphablending($src, true);

        if ($font !== null) {
            $fontSize = max(12, (int) (min($w, $h) * 0.07));
            // alpha 0=opaque, 127=transparan; 55 ≈ 57% opaque. Teks gelap di gambar
            // terang, teks putih di gambar gelap, agar watermark tetap terbaca.
            $bright = $this->averageLuminance($src) > 128;
            $color  = $bright
                ? imagecolorallocatealpha($src, 40, 40, 40, 55)
                : imagecolorallocatealpha($src, 255, 255, 255, 55);

            if ($color !== false) {
                $stepX = (int) ($w * 0.58);
                $stepY = (int) ($h * 0.52);

                for ($gy = (int) (-$h * 0.15); $gy < $h * 1.2; $gy += max(1, $stepY)) {
                    for ($gx = (int) (-$w * 0.10); $gx < $w * 1.1; $gx += max(1, $stepX)) {
                        imagettftext($src, $fontSize, 45, $gx, $gy, $color, $font, 'FOTO DEMO');
                    }
                }
            }
        } else {
            $color = imagecolorallocate($src, 200, 30, 30);
            imagestring($src, 5, (int) ($w / 2) - 40, (int) ($h / 2) - 8, 'FOTO DEMO', $color);
        }

        $saved = $this->saveImageAs($src, $path, $ext);
        imagedestroy($src);

        return $saved;
    }

    /**
     * Buat gambar placeholder berwarna dengan label (default "FOTO DEMO") berukuran
     * $w×$h ke $path. Warna deterministik dari $hmac. Mengembalikan bool sukses.
     */
    public function placeholder(string $path, int $w, int $h, string $hmac, string $label = 'FOTO DEMO'): bool
    {
        if (! $this->gdAvailable()) {
            return false;
        }

        $hue         = hexdec(substr($hmac, 0, 4)) % 360;
        [$r, $g, $b] = $this->hslToRgb($hue / 360, 0.45, 0.55);

        $img = imagecreatetruecolor($w, $h);
        $bg  = imagecolorallocate($img, $r, $g, $b);
        $fg  = imagecolorallocate($img, 255, 255, 255);
        imagefilledrectangle($img, 0, 0, $w - 1, $h - 1, $bg);

        $font     = $this->fontPath();
        $fontSize = max(12, (int) ($w * 0.06));

        if ($font !== null) {
            $bbox = imagettfbbox($fontSize, 0, $font, $label);
            $tx   = (int) (($w - ($bbox[2] - $bbox[0])) / 2) - $bbox[0];
            $ty   = (int) (($h + ($bbox[1] - $bbox[7])) / 2) - $bbox[7];
            imagettftext($img, $fontSize, 0, $tx, $ty, $fg, $font, $label);
        } else {
            imagestring($img, 5, (int) ($w / 2) - 40, (int) ($h / 2) - 8, $label, $fg);
        }

        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION)) ?: 'jpg';
        $ok  = $this->saveImageAs($img, $path, $ext);
        imagedestroy($img);

        return $ok;
    }

    /**
     * Sampel grid piksel; kembalikan luminansi rata-rata (0–255).
     */
    public function averageLuminance(GdImage $img): float
    {
        $w       = imagesx($img);
        $h       = imagesy($img);
        $samples = 20;
        $total   = 0.0;
        $count   = 0;

        for ($row = 0; $row < $samples; $row++) {
            for ($col = 0; $col < $samples; $col++) {
                $x     = (int) ($col / ($samples - 1) * ($w - 1));
                $y     = (int) ($row / ($samples - 1) * ($h - 1));
                $rgb   = imagecolorat($img, $x, $y);
                $total += 0.299 * (($rgb >> 16) & 0xFF) + 0.587 * (($rgb >> 8) & 0xFF) + 0.114 * ($rgb & 0xFF);
                $count++;
            }
        }

        // Grid selalu 20×20 → $count pasti > 0 (tak perlu penjaga pembagian nol).
        return $total / $count;
    }

    /**
     * Konversi HSL (masing-masing 0.0–1.0) ke array [R, G, B] (0–255).
     *
     * @return array{0:int,1:int,2:int}
     */
    public function hslToRgb(float $h, float $s, float $l): array
    {
        if ($s === 0.0) {
            $v = (int) round($l * 255);

            return [$v, $v, $v];
        }

        $q = $l < 0.5 ? $l * (1 + $s) : $l + $s - $l * $s;
        $p = 2 * $l - $q;

        $channel = static function (float $p, float $q, float $t): float {
            if ($t < 0) { $t += 1; }
            if ($t > 1) { $t -= 1; }
            if ($t < 1 / 6) { return $p + ($q - $p) * 6 * $t; }
            if ($t < 1 / 2) { return $q; }
            if ($t < 2 / 3) { return $p + ($q - $p) * (2 / 3 - $t) * 6; }

            return $p;
        };

        return [
            (int) round($channel($p, $q, $h + 1 / 3) * 255),
            (int) round($channel($p, $q, $h) * 255),
            (int) round($channel($p, $q, $h - 1 / 3) * 255),
        ];
    }
}
