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

class StatusKawinSpesifikEnum extends BaseEnum
{
    public const BELUM_KAWIN               = 1;
    public const KAWIN_TERCATAT            = 2;
    public const KAWIN_BELUM_TERCATAT      = 21;
    public const CERAIHIDUP_TERCATAT       = 3;
    public const CERAIHIDUP_BELUM_TERCATAT = 31;
    public const CERAIMATI                 = 4;

    /**
     * Override method all()
     */
    public static function all(): array
    {
        return [
            self::BELUM_KAWIN               => 'BELUM KAWIN',
            self::KAWIN_TERCATAT            => 'KAWIN TERCATAT',
            self::KAWIN_BELUM_TERCATAT      => 'KAWIN BELUM TERCATAT',
            self::CERAIHIDUP_TERCATAT       => 'CERAI HIDUP TERCATAT',
            self::CERAIHIDUP_BELUM_TERCATAT => 'CERAI HIDUP BELUM TERCATAT',
            self::CERAIMATI                 => 'CERAI MATI',
        ];
    }
}
