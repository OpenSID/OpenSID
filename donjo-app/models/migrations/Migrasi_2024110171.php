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

use App\Models\Config;
use App\Models\Setting;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_2024110171 extends MY_Model
{
    public function up()
    {
        $hasil = true;

        // Migrasi berdasarkan config_id
        $config_id = Config::appKey()->pluck('id')->toArray();

        foreach ($config_id as $id) {
            $hasil = $this->migrasi_2024100351($hasil, $id);
        }

        $hasil = $this->migrasi_2024100851($hasil);
        $hasil = $this->migrasi_2024100451($hasil);
        $hasil = $this->migrasi_202410651($hasil);

        return $this->migrasi_202412551($hasil);
    }

    protected function migrasi_2024100351($hasil, $id)
    {
        return $hasil && $this->tambah_setting([
            'judul'      => 'Versi Umum Setara',
            'key'        => 'compatible_version_general',
            'value'      => '2410',
            'keterangan' => 'Versi Umum Yang Setara',
            'jenis'      => 'text',
            'attribute'  => null,
            'kategori'   => 'default',
        ], $id);
    }

    protected function migrasi_2024100851($hasil)
    {
        if (! Schema::hasColumn('log_notifikasi_mandiri', 'token')) {
            Schema::table('log_notifikasi_mandiri', static function (Blueprint $table) {
                $table->longText('token')->nullable()->after('isi');
            });
        }

        if (! Schema::hasColumn('log_notifikasi_mandiri', 'device')) {
            Schema::table('log_notifikasi_mandiri', static function (Blueprint $table) {
                $table->longText('device')->nullable()->after('token');
            });
        }

        return $hasil;
    }

    protected function migrasi_2024100451($hasil)
    {
        $masihAda = Setting::where(['url' => 'analisis_master/clear'])->first();
        if ($masihAda) {
            $hasil = $hasil && $this->ubah_modul(
                ['slug' => 'master-analisis', 'url' => 'analisis_master/clear'],
                ['url' => 'analisis_master']
            );
            // harus diubah sekali saja, tidak boleh diulang
            DB::table('analisis_master')->where('lock', 1)->update(['lock' => 0]);
            DB::table('analisis_master')->where('lock', 2)->update(['lock' => 1]);
        }

        DB::table('analisis_indikator')->where('act_analisis', 2)->update(['act_analisis' => 0]);

        DB::table('setting_aplikasi')->whereIn('key', ['api_gform_credential', 'api_gform_id_script', 'api_gform_redirect_uri'])->update(['kategori' => 'Analisis']);

        DB::table('setting_modul')->whereIn('slug', ['master-analisis', 'pengaturan-analisis'])->delete();

        DB::table('setting_modul')->where('slug', 'analisis')->update(['url' => 'analisis_master', 'ikon' => 'fa-check-square']);

        $hasil = $hasil && $this->hapus_foreign_key('analisis_parameter', 'analisis_respon_subjek_fk', 'analisis_respon');
        $hasil = $hasil && $this->hapus_foreign_key('analisis_parameter', 'analisis_respon_hasil_subjek_fk', 'analisis_respon_hasil');

        $hasil = $hasil && $this->hapus_foreign_key('analisis_ref_subjek', 'analisis_respon_bukti_subjek_fk', 'analisis_respon_bukti');

        DB::table('setting_modul')->where('modul', 'analisis_kategori')->update(['modul' => 'Kategori / Variabel']);
        DB::table('setting_modul')->where('modul', 'analisis_indikator')->update(['modul' => 'Indikator & Pertanyaan']);
        DB::table('setting_modul')->where('modul', 'analisis_klasifikasi')->update(['modul' => 'Klasifikasi Analisis']);
        DB::table('setting_modul')->where('modul', 'analisis_periode')->update(['modul' => 'Periode Sensus / Survei']);
        DB::table('setting_modul')->where('modul', 'analisis_respon')->update(['modul' => 'Input Data Sensus / Survei']);
        DB::table('setting_modul')->where('modul', 'analisis_laporan')->update(['modul' => 'Laporan Hasil Klasifikasi']);
        DB::table('setting_modul')->where('modul', 'analisis_statistik_jawaban')->update(['modul' => 'Laporan Per Indikator']);

        return $hasil;
    }

    protected function migrasi_202410651($hasil)
    {
        return $hasil && $this->ubah_modul(
            ['slug' => 'statistik-kependudukan', 'url' => 'statistik/clear'],
            ['url' => 'statistik']
        );
    }

    protected function migrasi_202412551($hasil)
    {
        DB::table('tweb_penduduk_umur')
            ->where('sampai', 99999)
            ->update(['sampai' => 150]);

        return $hasil;
    }
}
