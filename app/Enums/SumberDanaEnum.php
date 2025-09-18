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

class SumberDanaEnum extends BaseEnum
{
    public const PAD               = 1;
    public const DANA_DESA         = 2;
    public const PAJAK_DAERAH      = 3;
    public const ALOKASI_DANA_DESA = 4;
    public const BANTUAN_PROVINSI  = 5;
    public const BANTUAN_KAB_KOTA  = 6;
    public const PENDAPATAN_LAIN   = 7;

    /**
     * Override method all()
     */
    public static function all(): array
    {
        return [
            self::PAD               => 'Pendapatan Asli Desa (PAD)',
            self::DANA_DESA         => 'Pendapatan Transfer (Dana Desa)',
            self::PAJAK_DAERAH      => 'Pendapatan Transfer (Bagian dari Hasil Pajak dan Retribusi Daerah Kabupaten/Kota)',
            self::ALOKASI_DANA_DESA => 'Pendapatan Transfer (Alokasi Dana Desa)',
            self::BANTUAN_PROVINSI  => 'Pendapatan Transfer (Bantuan Keuangan dari APBD Provinsi)',
            self::BANTUAN_KAB_KOTA  => 'Pendapatan Transfer (Bantuan Keuangan APBD Kabupaten/Kota)',
            self::PENDAPATAN_LAIN   => 'Pendapatan Lain',
        ];
    }
}
