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
 * Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

namespace App\Enums;

defined('BASEPATH') || exit('No direct script access allowed');

class SHDKEnum extends BaseEnum
{
    public const KEPALA_KELUARGA = 1;
    public const SUAMI           = 2;
    public const ISTRI           = 3;
    public const ANAK            = 4;
    public const MENANTU         = 5;
    public const CUCU            = 6;
    public const ORANGTUA        = 7;
    public const MERTUA          = 8;
    public const FAMILI_LAIN     = 9;
    public const PEMBANTU        = 10;
    public const LAINNYA         = 11;

    /**
     * Override method all()
     */
    public static function all(): array
    {
        return [
            self::KEPALA_KELUARGA => 'KEPALA KELUARGA',
            self::SUAMI           => 'SUAMI',
            self::ISTRI           => 'ISTRI',
            self::ANAK            => 'ANAK',
            self::MENANTU         => 'MENANTU',
            self::CUCU            => 'CUCU',
            self::ORANGTUA        => 'ORANGTUA',
            self::MERTUA          => 'MERTUA',
            self::FAMILI_LAIN     => 'FAMILI LAIN',
            self::PEMBANTU        => 'PEMBANTU',
            self::LAINNYA         => 'LAINNYA',
        ];
    }
}
