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

namespace App\Enums;

defined('BASEPATH') || exit('No direct script access allowed');

class PendudukBidangEnum extends BaseEnum
{
    public const SERVICE_KOMPUTER     = 1;
    public const OPERATOR_BULDOSER    = 2;
    public const OPERATOR_KOMPUTER    = 3;
    public const OPERATOR_GENSET      = 4;
    public const SERVICE_HP           = 5;
    public const RIAS_PENGANTIN       = 6;
    public const DESIGN_GRAFIS        = 7;
    public const MENJAHIT             = 8;
    public const MENULIS              = 9;
    public const REPORTER             = 10;
    public const SOSIAL_MEDIA_MANAJER = 11;
    public const MANAJEMEN_TRAINEE    = 12;
    public const KASIR                = 13;
    public const HRD                  = 14;
    public const GURU                 = 15;
    public const DIGITAL_MARKETING    = 16;
    public const CUSTOMER_SERVICES    = 17;
    public const WELDER               = 18;
    public const MEKANIK_ALAT_BERAT   = 19;
    public const TEKNISI_LISTRIK      = 20;
    public const INTERNET_MARKETING   = 21;

    /**
     * Override method all()
     */
    public static function all(): array
    {
        return [
            self::SERVICE_KOMPUTER     => 'Service Komputer',
            self::OPERATOR_BULDOSER    => 'Operator Buldoser',
            self::OPERATOR_KOMPUTER    => 'Operator Komputer',
            self::OPERATOR_GENSET      => 'Operator Genset',
            self::SERVICE_HP           => 'Service HP',
            self::RIAS_PENGANTIN       => 'Rias Pengantin',
            self::DESIGN_GRAFIS        => 'Design Grafis',
            self::MENJAHIT             => 'Menjahit',
            self::MENULIS              => 'Menulis',
            self::REPORTER             => 'Reporter',
            self::SOSIAL_MEDIA_MANAJER => 'Sosial Media Manajer',
            self::MANAJEMEN_TRAINEE    => 'Manajemen Trainee',
            self::KASIR                => 'Kasir',
            self::HRD                  => 'HRD',
            self::GURU                 => 'Guru',
            self::DIGITAL_MARKETING    => 'Digital Marketing',
            self::CUSTOMER_SERVICES    => 'Customer Services',
            self::WELDER               => 'Welder',
            self::MEKANIK_ALAT_BERAT   => 'Mekanik Alat Berat',
            self::TEKNISI_LISTRIK      => 'Teknisi Listrik',
            self::INTERNET_MARKETING   => 'Internet Marketing',
        ];
    }
}
