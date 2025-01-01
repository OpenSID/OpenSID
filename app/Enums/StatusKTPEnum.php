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

class StatusKTPEnum extends BaseEnum
{
    public const BELUM_REKAM            = 2;
    public const SUDAH_REKAM            = 3;
    public const CARD_PRINTED           = 4;
    public const PRINT_READY_RECORD     = 5;
    public const CARD_SHIPPED           = 6;
    public const SENT_FOR_CARD_PRINTING = 7;
    public const CARD_ISSUED            = 8;
    public const BELUM_WAJIB            = 1;

    /**
     * Override method all()
     */
    public static function all(): array
    {
        return [
            self::BELUM_REKAM            => 'BELUM REKAM',
            self::SUDAH_REKAM            => 'SUDAH REKAM',
            self::CARD_PRINTED           => 'CARD PRINTED',
            self::PRINT_READY_RECORD     => 'PRINT READY RECORD',
            self::CARD_SHIPPED           => 'CARD SHIPPED',
            self::SENT_FOR_CARD_PRINTING => 'SENT FOR CARD PRINTING',
            self::CARD_ISSUED            => 'CARD ISSUED',
            self::BELUM_WAJIB            => 'BELUM WAJIB',
        ];
    }
}
