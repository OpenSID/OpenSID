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

class TipeLinkEnum extends BaseEnum
{
    public const ARTIKEL_STATIS            = 1;
    public const KATEGORI_ARTIKEL          = 8;
    public const STATISTIK_PENDUDUK        = 2;
    public const STATISTIK_KELUARGA        = 3;
    public const STATISTIK_PROGRAM_BANTUAN = 4;
    public const STATISTIK_KESEHATAN       = 12;
    public const HALAMAN_STATIS_LAIN       = 5;
    public const ARTIKEL_KEUANGAN          = 6;
    public const KELOMPOK                  = 7;
    public const LEMBAGA                   = 11;
    public const DATA_SUPLEMEN             = 9;
    public const STATUS_IDM                = 10;
    public const EMBED                     = 88;
    public const EKSTERNAL                 = 99;

    /**
     * Override method all()
     */
    public static function all(): array
    {
        return [
            self::ARTIKEL_STATIS            => 'Artikel Statis',
            self::KATEGORI_ARTIKEL          => 'Kategori Artikel',
            self::STATISTIK_PENDUDUK        => 'Statistik Penduduk',
            self::STATISTIK_KELUARGA        => 'Statistik Keluarga',
            self::STATISTIK_PROGRAM_BANTUAN => 'Statistik Program Bantuan',
            self::STATISTIK_KESEHATAN       => 'Statistik Kesehatan',
            self::HALAMAN_STATIS_LAIN       => 'Halaman Statis Lain',
            self::ARTIKEL_KEUANGAN          => 'Artikel Keuangan',
            self::KELOMPOK                  => 'Kelompok',
            self::LEMBAGA                   => 'Lembaga',
            self::DATA_SUPLEMEN             => 'Data Suplemen',
            self::STATUS_IDM                => 'Status IDM',
            self::EMBED                     => 'Embed',
            self::EKSTERNAL                 => 'Eksternal',
        ];
    }
}
