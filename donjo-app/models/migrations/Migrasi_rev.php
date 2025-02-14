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

use App\Enums\AktifEnum;
use App\Models\Modul;
use App\Traits\Migrator;
use Illuminate\Support\Facades\DB;

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_rev
{
    use Migrator;

    public function up()
    {
        $this->hapusAksesInventarisApi();
        $this->ubahNamaInventaris();
        $this->ubahStatusWidget();
    }

    public function hapusAksesInventarisApi()
    {
        Modul::where('modul', 'like', '%api_inventaris%')->delete();
    }

    public function ubahNamaInventaris()
    {
        $moduls = Modul::where('modul', 'like', '%inventaris_%')->get();

        foreach ($moduls as $modul) {
            $modul->modul = ucwords(str_replace('_', ' ', $modul->modul));
            $modul->save();
        }

        $laporan = Modul::where('modul', 'laporan_inventaris')->first();
        if ($laporan) {
            $laporan->update(['modul' => 'Laporan Inventaris']);
        }

        Modul::where('slug', 'inventaris')->update(['url' => 'inventaris_master']);

        $this->createModul([
            'modul'       => 'Inventaris Tanah',
            'slug'        => 'inventaris-tanah',
            'url'         => 'inventaris_tanah',
            'ikon'        => '',
            'level'       => 0,
            'hidden'      => 2,
            'parent_slug' => 'sekretariat',
        ]);
    }

    public function ubahStatusWidget()
    {
        DB::table('widget')
            ->whereNotIn('enabled', AktifEnum::keys())
            ->update(['enabled' => AktifEnum::TIDAK_AKTIF]);
    }
}
