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
        Schema::table('analisis_respon_hasil', static function (Blueprint $table) {
            $table->foreign(['config_id'], 'analisis_respon_hasil_config_fk')->references(['id'])->on('config')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['desa_id'])->references(['id'])->on('config')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['dusun_id'])->references(['id'])->on('tweb_wil_clusterdesa')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['kelompok_id'])->references(['id'])->on('kelompok')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['keluarga_id'])->references(['id'])->on('tweb_keluarga')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['id_master'], 'analisis_respon_hasil_master_fk')->references(['id'])->on('analisis_master')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['penduduk_id'])->references(['id'])->on('tweb_penduduk')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['id_periode'], 'analisis_respon_hasil_periode_fk')->references(['id'])->on('analisis_periode')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['rtm_id'])->references(['id'])->on('tweb_rtm')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['rt_id'])->references(['id'])->on('tweb_wil_clusterdesa')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['rw_id'])->references(['id'])->on('tweb_wil_clusterdesa')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('analisis_respon_hasil', static function (Blueprint $table) {
            $table->dropForeign('analisis_respon_hasil_config_fk');
            $table->dropForeign('analisis_respon_hasil_desa_id_foreign');
            $table->dropForeign('analisis_respon_hasil_dusun_id_foreign');
            $table->dropForeign('analisis_respon_hasil_kelompok_id_foreign');
            $table->dropForeign('analisis_respon_hasil_keluarga_id_foreign');
            $table->dropForeign('analisis_respon_hasil_master_fk');
            $table->dropForeign('analisis_respon_hasil_penduduk_id_foreign');
            $table->dropForeign('analisis_respon_hasil_periode_fk');
            $table->dropForeign('analisis_respon_hasil_rtm_id_foreign');
            $table->dropForeign('analisis_respon_hasil_rt_id_foreign');
            $table->dropForeign('analisis_respon_hasil_rw_id_foreign');
        });
    }
};
