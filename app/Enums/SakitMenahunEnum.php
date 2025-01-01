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

class SakitMenahunEnum extends BaseEnum
{
    public const JANTUNG               = 1;
    public const LEVER                 = 2;
    public const PARU_PARU             = 3;
    public const KANKER                = 4;
    public const STROKE                = 5;
    public const DIABETES_MELITUS      = 6;
    public const GINJAL                = 7;
    public const MALARIA               = 8;
    public const LEPRA_KUSTA           = 9;
    public const HIV_AIDS              = 10;
    public const GILA_STRESS           = 11;
    public const TBC                   = 12;
    public const ASTHMA                = 13;
    public const TIDAK_ADA_TIDAK_SAKIT = 14;

    /**
     * Override method all()
     */
    public static function all(): array
    {
        return [
            self::JANTUNG               => 'JANTUNG',
            self::LEVER                 => 'LEVER',
            self::PARU_PARU             => 'PARU-PARU',
            self::KANKER                => 'KANKER',
            self::STROKE                => 'STROKE',
            self::DIABETES_MELITUS      => 'DIABETES MELITUS',
            self::GINJAL                => 'GINJAL',
            self::MALARIA               => 'MALARIA',
            self::LEPRA_KUSTA           => 'LEPRA/KUSTA',
            self::HIV_AIDS              => 'HIV/AIDS',
            self::GILA_STRESS           => 'GILA/STRESS',
            self::TBC                   => 'TBC',
            self::ASTHMA                => 'ASTHMA',
            self::TIDAK_ADA_TIDAK_SAKIT => 'TIDAK ADA/TIDAK SAKIT',
        ];
    }
}
