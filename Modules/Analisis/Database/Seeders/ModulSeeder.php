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

namespace Modules\Analisis\Database\Seeders;

use App\Actions\GrupAkses\UpsertGrupAkses;
use App\Models\GrupAkses;
use App\Models\Modul;
use App\Models\UserGrup;
use App\Traits\Migrator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class ModulSeeder extends Seeder
{
    use Migrator;

    public function run()
    {
        Model::unguard();

        $id = identitas('id');

        $this->createModul([
            'config_id' => $id,
            'modul'     => 'Analisis',
            'slug'      => 'analisis',
            'url'       => 'analisis_master',
            'ikon'      => 'fa-check-square',
            'level'     => 2,
            'hidden'    => 0,
            'parent'    => 0,
        ]);

        $this->createModuls([
            [
                'modul'       => 'Kategori / Variabel',
                'slug'        => 'analisis-kategori',
                'url'         => 'analisis_kategori',
                'aktif'       => 1,
                'level'       => 0,
                'hidden'      => 2,
                'parent_slug' => 'analisis',
            ],
            [
                'modul'       => 'Indikator & Pertanyaan',
                'slug'        => 'analisis-indikator',
                'url'         => 'analisis_indikator',
                'aktif'       => 1,
                'level'       => 0,
                'hidden'      => 2,
                'parent_slug' => 'analisis',
            ],
            [
                'modul'       => 'Klasifikasi Analisis',
                'slug'        => 'analisis-klasifikasi',
                'url'         => 'analisis_klasifikasi',
                'aktif'       => 1,
                'level'       => 0,
                'hidden'      => 2,
                'parent_slug' => 'analisis',
            ],
            [
                'modul'       => 'Periode Sensus / Survei',
                'slug'        => 'analisis-periode',
                'url'         => 'analisis_periode',
                'aktif'       => 1,
                'level'       => 0,
                'hidden'      => 2,
                'parent_slug' => 'analisis',
            ],
            [
                'modul'       => 'Input Data Sensus / Survei',
                'slug'        => 'analisis-respon',
                'url'         => 'analisis_respon',
                'aktif'       => 1,
                'level'       => 0,
                'hidden'      => 2,
                'parent_slug' => 'analisis',
            ],
            [
                'modul'       => 'Laporan Hasil Klasifikasi',
                'slug'        => 'analisis-laporan',
                'url'         => 'analisis_laporan',
                'aktif'       => 1,
                'level'       => 0,
                'hidden'      => 2,
                'parent_slug' => 'analisis',
            ],
            [
                'modul'       => 'Laporan Per Indikator',
                'slug'        => 'analisis-statistik-jawaban',
                'url'         => 'analisis_statistik_jawaban',
                'aktif'       => 1,
                'level'       => 0,
                'hidden'      => 2,
                'parent_slug' => 'analisis',
            ],
        ]);

        $grupId = UserGrup::getGrupId('kasi-kesejahteraan');

        $akses = [
            'analisis'                   => 0,
            'analisis-kategori'          => GrupAkses::HAPUS,
            'analisis-indikator'         => GrupAkses::HAPUS,
            'analisis-klasifikasi'       => GrupAkses::HAPUS,
            'analisis-periode'           => GrupAkses::HAPUS,
            'analisis-respon'            => GrupAkses::HAPUS,
            'analisis-laporan'           => GrupAkses::HAPUS,
            'analisis-statistik-jawaban' => GrupAkses::HAPUS,
        ];

        $handler = new UpsertGrupAkses();

        foreach ($akses as $slug => $izin) {
            $handler->handle([
                'id_grup'  => $grupId,
                'id_modul' => Modul::where('slug', $slug)->value('id'),
                'akses'    => $izin,
            ]);
        }
    }
}
