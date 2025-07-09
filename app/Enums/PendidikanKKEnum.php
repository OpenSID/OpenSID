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

class PendidikanKKEnum extends BaseEnum
{
    public const BELUM_SEKOLAH  = 1;
    public const BELUM_TAMAT_SD = 2;
    public const TAMAT_SD       = 3;
    public const SLTP           = 4;
    public const SLTA           = 5;
    public const DIPLOMA        = 6;
    public const AKADEMI        = 7;
    public const STRATA_I       = 8;
    public const STRATA_II      = 9;
    public const STRATA_III     = 10;

    /**
     * Override method all()
     */
    public static function all(): array
    {
        return [
            self::BELUM_SEKOLAH  => 'TIDAK / BELUM SEKOLAH',
            self::BELUM_TAMAT_SD => 'BELUM TAMAT SD / SEDERAJAT',
            self::TAMAT_SD       => 'TAMAT SD / SEDERAJAT',
            self::SLTP           => 'SLTP / SEDERAJAT',
            self::SLTA           => 'SLTA / SEDERAJAT',
            self::DIPLOMA        => 'DIPLOMA I / II',
            self::AKADEMI        => 'AKADEMI/ DIPLOMA III / S. MUDA',
            self::STRATA_I       => 'DIPLOMA IV / STRATA I',
            self::STRATA_II      => 'STRATA II',
            self::STRATA_III     => 'STRATA III',
        ];
    }
}
