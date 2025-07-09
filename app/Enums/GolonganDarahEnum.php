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

namespace App\Enums;

defined('BASEPATH') || exit('No direct script access allowed');

class GolonganDarahEnum extends BaseEnum
{
    public const A          = 1;
    public const B          = 2;
    public const AB         = 3;
    public const O          = 4;
    public const A_PLUS     = 5;
    public const A_MINUS    = 6;
    public const B_PLUS     = 7;
    public const B_MINUS    = 8;
    public const AB_PLUS    = 9;
    public const AB_MINUS   = 10;
    public const O_PLUS     = 11;
    public const O_MINUS    = 12;
    public const TIDAK_TAHU = 13;

    /**
     * Override method all()
     */
    public static function all(): array
    {
        return [
            self::A          => 'A',
            self::B          => 'B',
            self::AB         => 'AB',
            self::O          => 'O',
            self::A_PLUS     => 'A+',
            self::A_MINUS    => 'A-',
            self::B_PLUS     => 'B+',
            self::B_MINUS    => 'B-',
            self::AB_PLUS    => 'AB+',
            self::AB_MINUS   => 'AB-',
            self::O_PLUS     => 'O+',
            self::O_MINUS    => 'O-',
            self::TIDAK_TAHU => 'TIDAK TAHU',
        ];
    }
}
