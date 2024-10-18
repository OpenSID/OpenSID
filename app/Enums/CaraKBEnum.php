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

class CaraKBEnum extends BaseEnum
{
    public const PIL                = 1;
    public const IUD                = 2;
    public const SUNTIK             = 3;
    public const KONDOM             = 4;
    public const SUSUK_KB           = 5;
    public const STERILISASI_WANITA = 6;
    public const STERILISASI_PRIA   = 7;
    public const LAINNYA            = 99;
    public const TIDAK_MENGGUNAKAN  = 100;

    /**
     * Override method all()
     */
    public static function all(): array
    {
        return [
            self::PIL                => 'Pil',
            self::IUD                => 'IUD',
            self::SUNTIK             => 'Suntik',
            self::KONDOM             => 'Kondom',
            self::SUSUK_KB           => 'Susuk KB',
            self::STERILISASI_WANITA => 'Sterilisasi Wanita',
            self::STERILISASI_PRIA   => 'Sterilisasi Pria',
            self::LAINNYA            => 'Lainnya',
            self::TIDAK_MENGGUNAKAN  => 'Tidak Menggunakan',
        ];
    }
}
