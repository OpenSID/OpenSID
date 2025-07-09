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

class AsuransiEnum extends BaseEnum
{
    public const TIDAK_BELUM_PUNYA               = 1;
    public const BPJS_PENERIMA_BANTUAN_IURAN     = 2;
    public const BPJS_NON_PENERIMA_BANTUAN_IURAN = 3;
    public const BPJS_BANTUAN_DAERAH             = 4;
    public const ASURANSI_LAINNYA                = 99;

    /**
     * Override method all()
     */
    public static function all(): array
    {
        return [
            self::TIDAK_BELUM_PUNYA               => 'Tidak/Belum Punya',
            self::BPJS_PENERIMA_BANTUAN_IURAN     => 'BPJS Penerima Bantuan Iuran',
            self::BPJS_NON_PENERIMA_BANTUAN_IURAN => 'BPJS Non Penerima Bantuan Iuran',
            self::BPJS_BANTUAN_DAERAH             => 'BPJS Bantuan Daerah',
            self::ASURANSI_LAINNYA                => 'Asuransi Lainnya',
        ];
    }
}
