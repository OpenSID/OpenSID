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

class InventarisSubMenuEnum extends BaseEnum
{
    public const LAPORAN = [
        'key'    => 0,
        'slug'   => 'laporan_inventaris',
        'label'  => 'Laporan Semua Aset',
        'header' => 'Laporan Keseluruhan Aset Desa',
    ];
    public const TANAH = [
        'key'    => 1,
        'slug'   => 'inventaris_tanah',
        'label'  => 'Tanah',
        'header' => 'Inventaris Tanah',
    ];
    public const PERALATAN = [
        'key'    => 2,
        'slug'   => 'inventaris_peralatan',
        'label'  => 'Peralatan Dan Mesin',
        'header' => 'Inventaris Peralatan dan Mesin',
    ];
    public const GEDUNG = [
        'key'    => 3,
        'slug'   => 'inventaris_gedung',
        'label'  => 'Gedung dan Bangunan',
        'header' => 'Inventaris Gedung dan Bangunan',
    ];
    public const JALAN = [
        'key'    => 4,
        'slug'   => 'inventaris_jalan',
        'label'  => 'Jalan, Irigasi, dan Jaringan',
        'header' => 'Inventaris Jalan, Irigasi, dan Jaringan',
    ];
    public const ASET = [
        'key'    => 5,
        'slug'   => 'inventaris_asset',
        'label'  => 'Aset Tetap Lainnya',
        'header' => 'Inventaris Aset Tetap Lainnya',
    ];
    public const KONSTRUKSI = [
        'key'    => 6,
        'slug'   => 'inventaris_kontruksi',
        'label'  => 'Konstruksi dalam pengerjaan',
        'header' => 'Inventaris Konstruksi dalam Pengerjaan',
    ];

    public static $data = [
        self::LAPORAN,
        self::TANAH,
        self::PERALATAN,
        self::GEDUNG,
        self::JALAN,
        self::ASET,
        self::KONSTRUKSI,
    ];

    /**
     * {@inheritDoc}
     */
    public static function all(): array
    {
        return collect(self::$data)->pluck('label', 'slug')->toArray();
    }

    /**
     * Get all key label
     */
    public static function allKeyLabel(): array
    {
        return collect(self::$data)->pluck('label', 'key')->toArray();
    }

    /**
     * Get slug from key
     */
    public static function slugFromKey(mixed $key): ?string
    {
        $item = collect(self::$data)->firstWhere('key', $key);

        return $item ? $item['slug'] : null;
    }

    /**
     * Get key form slug
     */
    public static function keyFromSlug(mixed $slug): ?string
    {
        $item = collect(self::$data)->firstWhere('slug', $slug);

        return $item ? $item['key'] : null;
    }
}
