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

namespace Modules\BukuTamu\Database\Seeders;

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
            'modul'     => 'Buku Tamu',
            'slug'      => 'buku-tamu',
            'url'       => '',
            'ikon'      => 'fa-book',
            'level'     => 2,
            'parent'    => 0,
        ]);

        // Sub Menu
        $this->createModuls([
            [
                'modul'       => 'Data Tamu',
                'slug'        => 'data-tamu',
                'url'         => 'buku_tamu',
                'ikon'        => 'fa-bookmark-o',
                'level'       => 2,
                'parent_slug' => 'buku-tamu',
            ],
            [
                'modul'       => 'Data Kepuasan',
                'slug'        => 'data-kepuasan',
                'url'         => 'buku_kepuasan',
                'ikon'        => 'fa-smile-o',
                'level'       => 2,
                'parent_slug' => 'buku-tamu',
            ],
            [
                'modul'       => 'Data Pertanyaan',
                'slug'        => 'data-pertanyaan',
                'url'         => 'buku_pertanyaan',
                'ikon'        => 'fa-question',
                'level'       => 2,
                'parent_slug' => 'buku-tamu',
            ],
            [
                'modul'       => 'Data Keperluan',
                'slug'        => 'data-keperluan',
                'url'         => 'buku_keperluan',
                'ikon'        => 'fa-send',
                'level'       => 2,
                'parent_slug' => 'buku-tamu',
            ],
        ]);
    }
}
