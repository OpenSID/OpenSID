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

use App\Enums\StatusEnum;
use App\Models\Config;
use App\Models\FormatSurat;
use App\Observers\ClearCacheObserver;
use App\Services\Install\CreateGrupAksesService;
use App\Traits\Migrator;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_2024120171 extends MY_Model
{
    use Migrator;

    public function up()
    {
        $hasil = true;

        // Migrasi berdasarkan config_id
        $config_id = Config::appKey()->pluck('id')->toArray();

        foreach ($config_id as $id) {
            $hasil = $this->migrasi_2024110651($hasil, $id);
        }

        $hasil = $this->migrasi_2024110351($hasil);
        $hasil = $this->migrasi_2024110652($hasil);
        $hasil = $this->migrasi_2024111251($hasil);
        $hasil = $this->migrasi_2024112071($hasil);
        $hasil = $this->migrasi_2024112551($hasil);

        return $this->migrasi_2024112651($hasil);
    }

    private function migrasi_2024110651($hasil, $id)
    {
        return $hasil && (new CreateGrupAksesService())->run($id);
    }

    private function migrasi_2024110652($hasil)
    {
        copyFavicon();

        return $hasil;
    }

    protected function migrasi_2024110351($hasil)
    {
        $hasil = $hasil && $this->hapus_foreign_key('lokasi', 'persil_peta_fk', 'persil');
        $hasil = $hasil && $this->tambahForeignKey('persil_peta_fk', 'persil', 'id_peta', 'area', 'id', true);
        $hasil = $hasil && $this->hapus_foreign_key('lokasi', 'mutasi_cdesa_peta_fk', 'mutasi_cdesa');

        return $hasil && $this->tambahForeignKey('mutasi_cdesa_peta_fk', 'mutasi_cdesa', 'id_peta', 'area', 'id', true);
    }

    protected function migrasi_2024111251($hasil)
    {
        FormatSurat::where('url_surat', 'sistem-surat-keterangan-pengantar-rujukcerai')->where('jenis', FormatSurat::TINYMCE_SISTEM)->delete();

        return $hasil;
    }

    protected function migrasi_2024112071($hasil)
    {
        if (! Schema::hasColumn('suplemen', 'status')) {
            Schema::table('suplemen', static function (Blueprint $table) {
                $table->tinyInteger('status')->default(1)->comment('1 = Aktif, 0 = Nonaktif');
            });
        }

        if (! Schema::hasColumn('suplemen', 'sumber')) {
            Schema::table('suplemen', static function (Blueprint $table) {
                $table->enum('sumber', ['OpenSID', 'OpenKab'])->default('OpenSID');
            });
        }

        if (! Schema::hasColumn('suplemen', 'form_isian')) {
            Schema::table('suplemen', static function (Blueprint $table) {
                $table->longText('form_isian')->nullable()->comment('Menyimpan data formulir dinamis tambahan sebagai JSON atau teks');
            });
        }

        return $hasil;
    }

    protected function migrasi_2024112551($hasil)
    {
        $query = <<<'SQL'
                        delete t1
                        FROM grup_akses t1
                        INNER JOIN grup_akses t2
                        WHERE
                            t1.id > t2.id AND
                            t1.config_id = t2.config_id AND
                            t1.id_grup = t2.id_grup and
                            t1.id_modul = t2.id_modul
            SQL;
        DB::statement($query);

        $this->tambahIndeks('grup_akses', 'config_id, id_grup, id_modul', 'UNIQUE', true);

        return $hasil;
    }

    protected function migrasi_2024112651($hasil)
    {
        if (Schema::hasColumn('shortcut', 'akses')) {
            Schema::table('shortcut', static function ($table) {
                $table->dropColumn('akses');
            });
        }

        if (Schema::hasColumn('shortcut', 'link')) {
            Schema::table('shortcut', static function ($table) {
                $table->dropColumn('link');
            });
        }

        if (Schema::hasColumn('shortcut', 'jenis_query')) {
            DB::table('shortcut')->where('jenis_query', 1)->update(['raw_query' => null, 'status' => StatusEnum::TIDAK]);

            Schema::table('shortcut', static function (Blueprint $table) {
                $table->dropColumn('jenis_query');
            });

            (new ClearCacheObserver())->clearAllCache();
        }

        return $hasil;
    }
}
