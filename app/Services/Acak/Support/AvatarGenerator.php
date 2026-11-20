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

/**
 * Pembangkit avatar kartun deterministik untuk foto warga/pamong.
 *
 * Utama: ambil wajah kartun 200×200 dari DiceBear (butuh internet). Cadangan
 * luring: avatar inisial berbasis GD dengan warna sesuai jenis kelamin. Seed HMAC
 * menjamin orang yang sama selalu memperoleh wajah yang sama antar-proses.
 *
 * Menulis berkas utama `acak-{id}.jpg` dan thumbnail `kecil_acak-{id}.jpg`.
 * Mengembalikan nama berkas utama, atau '' bila gagal (mis. GD tidak tersedia).
 */
final class AvatarGenerator
{
    public function __construct(private readonly ImageToolkit $toolkit)
    {
    }

    public function generate(int $id, int $sex, string $synName, string $uploadDir): string
    {
        if (! is_dir($uploadDir) || ! $this->toolkit->gdAvailable()) {
            return '';
        }

        $hmac = $this->toolkit->hmac('avatar', (string) $id);

        $viaDiceBear = $this->fromDiceBear($id, $sex, $hmac, $uploadDir);
        if ($viaDiceBear !== '') {
            return $viaDiceBear;
        }

        return $this->fromInitials($id, $sex, $synName, $hmac, $uploadDir);
    }

    /**
     * Ambil avatar 200×200 dari DiceBear lalu tulis JPEG utama + thumbnail 150×150.
     * Laki-laki → palet biru; perempuan → palet merah muda. '' bila gagal.
     */
    private function fromDiceBear(int $id, int $sex, string $hmac, string $uploadDir): string
    {
        $params = $sex === 1
            ? implode('&', [
                'backgroundColor[]=b6e3f4&backgroundColor[]=c0aede&backgroundColor[]=d1d4f9',
                'hair[]=fonze&hair[]=mrT&hair[]=mrClean&hair[]=dannyPhantom&hair[]=dougFunny',
                'eyebrows[]=up&eyebrows[]=down',
                'facialHairProbability=30',
                'earringsProbability=5',
            ])
            : implode('&', [
                'backgroundColor[]=ffd5dc&backgroundColor[]=ffdfbf&backgroundColor[]=fde68a',
                'hair[]=full&hair[]=pixie&hair[]=turban',
                'eyebrows[]=eyelashesUp&eyebrows[]=eyelashesDown',
                'facialHairProbability=0',
                'earringsProbability=60',
            ]);

        $url     = "https://api.dicebear.com/9.x/micah/png?seed={$hmac}&size=200&{$params}";
        $pngData = $this->toolkit->fetchRemote($url, 512);
        if ($pngData === null) {
            return '';
        }

        $src = @imagecreatefromstring($pngData);
        if ($src === false) {
            return '';
        }

        $filename  = "acak-{$id}.jpg";
        $thumbname = "kecil_acak-{$id}.jpg";

        // Komposit di atas putih (PNG DiceBear bisa transparan) lalu simpan JPEG.
        $full  = imagecreatetruecolor(200, 200);
        $white = imagecolorallocate($full, 255, 255, 255);
        imagefilledrectangle($full, 0, 0, 199, 199, $white);
        imagecopy($full, $src, 0, 0, 0, 0, 200, 200);
        imagejpeg($full, $uploadDir . $filename, 88);
        imagedestroy($full);

        $thumb  = imagecreatetruecolor(150, 150);
        $white2 = imagecolorallocate($thumb, 255, 255, 255);
        imagefilledrectangle($thumb, 0, 0, 149, 149, $white2);
        imagecopyresampled($thumb, $src, 0, 0, 0, 0, 150, 150, 200, 200);
        imagejpeg($thumb, $uploadDir . $thumbname, 88);
        imagedestroy($thumb);
        imagedestroy($src);

        return $filename;
    }

    /**
     * Avatar inisial berwarna (cadangan luring). Laki-laki → rentang biru baja
     * (200–239°); perempuan → merah muda/magenta (320–359°).
     */
    private function fromInitials(int $id, int $sex, string $synName, string $hmac, string $uploadDir): string
    {
        $hue = $sex === 1
            ? 200 + (hexdec(substr($hmac, 0, 4)) % 40)
            : 320 + (hexdec(substr($hmac, 0, 4)) % 40);

        [$r, $g, $b] = $this->toolkit->hslToRgb($hue / 360, 0.55, 0.45);

        $words    = array_values(array_filter(explode(' ', $synName)));
        $initials = '';
        foreach (array_slice($words, 0, 2) as $w) {
            $initials .= mb_strtoupper(mb_substr($w, 0, 1));
        }
        if ($initials === '') {
            $initials = 'X';
        }

        $font     = $this->toolkit->fontPath();
        $filename = "acak-{$id}.jpg";

        foreach ([[200, ''], [150, 'kecil_']] as [$dim, $prefix]) {
            $img = imagecreatetruecolor($dim, $dim);
            $bg  = imagecolorallocate($img, $r, $g, $b);
            $fg  = imagecolorallocate($img, 255, 255, 255);
            imagefill($img, 0, 0, $bg);

            if ($font !== null) {
                $fontSize = (int) ($dim * 0.40);
                $bbox     = imagettfbbox($fontSize, 0, $font, $initials);
                $tx       = (int) (($dim - ($bbox[2] - $bbox[0])) / 2) - $bbox[0];
                $ty       = (int) (($dim + ($bbox[1] - $bbox[7])) / 2) - $bbox[7];
                imagettftext($img, $fontSize, 0, $tx, $ty, $fg, $font, $initials);
            } else {
                imagestring($img, 5, (int) ($dim / 2) - 8, (int) ($dim / 2) - 8, $initials, $fg);
            }

            imagejpeg($img, $uploadDir . $prefix . $filename, 85);
            imagedestroy($img);
        }

        return $filename;
    }
}
