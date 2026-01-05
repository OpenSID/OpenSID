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

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('analisis_respon', static function (Blueprint $table) {
            $table->integer('id_indikator')->nullable()->index('id_indikator');
            $table->integer('config_id')->index('analisis_respon_config_fk');
            $table->integer('id_parameter')->nullable();
            $table->integer('id_subjek')->nullable();
            $table->integer('id_periode')->nullable()->index('id_periode');
            $table->integer('penduduk_id')->nullable()->index('analisis_respon_penduduk_id_foreign');
            $table->integer('keluarga_id')->nullable()->index('analisis_respon_keluarga_id_foreign');
            $table->integer('kelompok_id')->nullable()->index('analisis_respon_kelompok_id_foreign');
            $table->integer('rtm_id')->nullable()->index('analisis_respon_rtm_id_foreign');
            $table->integer('desa_id')->nullable()->index('analisis_respon_desa_id_foreign');
            $table->integer('dusun_id')->nullable()->index('analisis_respon_dusun_id_foreign');
            $table->integer('rw_id')->nullable()->index('analisis_respon_rw_id_foreign');
            $table->integer('rt_id')->nullable()->index('analisis_respon_rt_id_foreign');

            $table->index(['id_parameter', 'id_subjek'], 'id_parameter');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('analisis_respon');
    }
};
