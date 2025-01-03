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

namespace Modules\Kehadiran\Database\Seeders;

use App\Traits\Migrator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class ModulSeeder extends Seeder
{
    use Migrator;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $id = identitas('id');

        // Menu Utama
        $this->createModul([
            'config_id' => $id,
            'modul'     => 'Kehadiran',
            'slug'      => 'kehadiran',
            'url'       => '',
            'ikon'      => 'fa-calendar-check-o',
            'level'     => 0,
            'parent'    => 0,
        ]);

        // Sub Menu
        $this->createModuls([
            [
                'modul'       => 'Jam Kerja',
                'slug'        => 'jam-kerja',
                'url'         => 'kehadiran_jam_kerja',
                'ikon'        => 'fa-clock-o',
                'urut'        => 2,
                'level'       => 0,
                'parent_slug' => 'kehadiran',
            ],
            [
                'modul'       => 'Hari Libur',
                'slug'        => 'hari-libur',
                'url'         => 'kehadiran_hari_libur',
                'ikon'        => 'fa-calendar',
                'urut'        => 2,
                'level'       => 0,
                'parent_slug' => 'kehadiran',
            ],
            [
                'modul'       => 'Rekapitulasi',
                'slug'        => 'rekapitulasi',
                'url'         => 'kehadiran_rekapitulasi',
                'ikon'        => 'fa-list',
                'urut'        => 2,
                'level'       => 0,
                'parent_slug' => 'kehadiran',
            ],
            [
                'modul'       => 'Pengaduan',
                'slug'        => 'kehadiran-pengaduan',
                'url'         => 'kehadiran_pengaduan',
                'ikon'        => 'fa-exclamation',
                'urut'        => 2,
                'level'       => 0,
                'parent_slug' => 'kehadiran',
            ],
        ]);
    }
}
