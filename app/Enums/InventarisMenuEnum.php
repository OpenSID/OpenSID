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

enum InventarisMenuEnum
{
    case DAFTAR;
    case MUTASI;
    case LAPORAN;
    case LAPORAN_MUTASI;

    public static function getMenus(string $controller): array
    {
        if ($controller === 'laporan_inventaris') {
            return [
                self::LAPORAN,
                self::LAPORAN_MUTASI,
            ];
        }

        $menus = [self::DAFTAR];
        if ($controller !== 'inventaris_kontruksi') {
            $menus[] = self::MUTASI;
        }

        return $menus;
    }

    public function label(): string
    {
        return match ($this) {
            self::DAFTAR         => 'Daftar Inventaris',
            self::MUTASI         => 'Daftar Mutasi',
            self::LAPORAN        => 'Laporan Semua Aset',
            self::LAPORAN_MUTASI => 'Laporan Aset Yang Dihapus',
        };
    }

    public function url(string $controller): string
    {
        return match ($this) {
            self::DAFTAR         => site_url(str_replace('_mutasi', '', $controller)),
            self::MUTASI         => site_url(str_replace('_mutasi_mutasi', '_mutasi', $controller . '_mutasi')),
            self::LAPORAN        => site_url('laporan_inventaris'),
            self::LAPORAN_MUTASI => site_url('laporan_inventaris/mutasi'),
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::DAFTAR, self::LAPORAN, self::LAPORAN_MUTASI => 'fa fa-list',
            self::MUTASI => 'fa fa-share',
        };
    }

    public function tip(): int
    {
        return match ($this) {
            self::DAFTAR, self::LAPORAN => 1,
            self::MUTASI, self::LAPORAN_MUTASI => 2,
        };
    }
}
