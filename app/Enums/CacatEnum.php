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

class CacatEnum extends BaseEnum
{
    public const CACAT_FISIK            = 1;
    public const CACAT_NETRA_BUTA       = 2;
    public const CACAT_RUNGU_WICARA     = 3;
    public const CACAT_MENTAL_JIWA      = 4;
    public const CACAT_FISIK_DAN_MENTAL = 5;
    public const CACAT_LAINNYA          = 6;
    public const TIDAK_CACAT            = 7;

    /**
     * Override method all()
     */
    public static function all(): array
    {
        return [
            self::CACAT_FISIK            => 'CACAT FISIK',
            self::CACAT_NETRA_BUTA       => 'CACAT NETRA/BUTA',
            self::CACAT_RUNGU_WICARA     => 'CACAT RUNGU/WICARA',
            self::CACAT_MENTAL_JIWA      => 'CACAT MENTAL/JIWA',
            self::CACAT_FISIK_DAN_MENTAL => 'CACAT FISIK DAN MENTAL',
            self::CACAT_LAINNYA          => 'CACAT LAINNYA',
            self::TIDAK_CACAT            => 'TIDAK CACAT',
        ];
    }
}
