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

namespace App\Libraries;

use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

defined('BASEPATH') || exit('No direct script access allowed');

// Library ini berasal dari https://github.com/esyede/captcha
class Captcha
{
    protected static $fonts       = [];
    protected static $backgrounds = [];
    protected static $characters;
    protected static $case_sensitive = false;

    public static function make($case_sensitive = false): bool
    {
        if (empty(static::$backgrounds)) {
            static::backgrounds();
        }

        if (empty(static::$fonts)) {
            static::fonts();
        }

        static::$case_sensitive = (bool) $case_sensitive;
        static::$characters     = str_replace(
            ['0', '1', '5', 'i', 'I', 'k', 'K', 'l', 'L', 'o', 'O', 's', 'S', 'w', 'W'],
            ['6', '4', '8', '2', '3', 'z', 'Z', 'p', 'P', 'h', 'H', 'x', 'X', 'v', 'V'],
            Str::random(5)
        );

        $characters            = static::$case_sensitive ? static::$characters : strtolower(static::$characters);
        ci()->session->captcha = Hash::make($characters);

        $bg   = static::background();
        $font = static::font();
        $info = getimagesize($bg);
        $old  = match ($info['mime']) {
            'image/jpg', 'image/jpeg' => imagecreatefromjpeg($bg),
            'image/gif' => imagecreatefromgif($bg),
            'image/png' => imagecreatefrompng($bg),
            default     => throw new Exception('Only JPG, PNG and GIF are supported for backgrounds.'),
        };

        // default settings
        $width  = 120;
        $height = 30;
        $space  = 20;

        $new = imagecreatetruecolor($width, $height);
        $bg  = imagecolorallocate($new, 255, 255, 255);

        imagefilledrectangle($new, 0, 0, $width - 1, $height - 1, $bg);
        imagecopyresampled($new, $old, 0, 0, 0, 0, $width, $height, $info[0], $info[1]);
        imagedestroy($old);

        $color = md5(Str::random(5));

        for ($i = 0; $i < 5; $i++) {
            $colors = [
                hexdec(substr($color, random_int(0, 31), 2)),
                hexdec(substr($color, random_int(0, 31), 2)),
                hexdec(substr($color, random_int(0, 31), 2)),
                hexdec(substr($color, random_int(0, 31), 2)),
                hexdec(substr($color, random_int(0, 31), 2)),
            ];

            $gap = 10 + ($i * $space);
            $w   = random_int(-10, 15);
            $h   = random_int($height - 10, $height - 5);
            $fg  = imagecolorallocate($new, $colors[random_int(1, 3)], $colors[random_int(1, 4)], $colors[random_int(0, 4)]);

            imagettftext($new, random_int(18, 20), $w, $gap, $h, $fg, $font, static::$characters[$i]);
        }

        header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate');
        header('Pragma: no-cache');
        header('Content-type: image/png');
        header('Content-Disposition: inline; filename=captcha.png');

        return imagepng($new);
    }

    public static function check($value): bool
    {
        $value = trim((string) (static::$case_sensitive ? $value : strtolower((string) $value)));
        $hash  = ci()->session->captcha;

        return $value && $hash && Hash::check($value, $hash);
    }

    protected static function fonts()
    {
        $fonts         = glob(FCPATH . 'assets/captcha/fonts/' . '*.ttf');
        static::$fonts = (is_array($fonts) && $fonts !== []) ? $fonts : [];
    }

    protected static function backgrounds()
    {
        $backgrounds         = glob(FCPATH . 'assets/captcha/backgrounds/' . '*.png');
        static::$backgrounds = (is_array($backgrounds) && $backgrounds !== []) ? $backgrounds : [];
    }

    protected static function background()
    {
        if (empty(static::$backgrounds)) {
            throw new Exception('No backgrounds found to operate with.');
        }

        return static::$backgrounds[array_rand(static::$backgrounds)];
    }

    protected static function font()
    {
        if (empty(static::$fonts)) {
            throw new Exception('No fonts found to operate with.');
        }

        return static::$fonts[array_rand(static::$fonts)];
    }
}
